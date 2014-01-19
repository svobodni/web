<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CkeditorModule\Listeners;

use CmsModule\Content\Forms\Controls\Events\ContentEditorArgs;
use CmsModule\Content\Forms\Controls\Events\ContentEditorEvents;
use Doctrine\Common\EventSubscriber;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormaterListener implements EventSubscriber
{

	/** @var string */
	protected $basePath;

	/** @var \Nette\DI\Container */
	protected $context;

	/** @var bool */
	private $enableThumbnails = FALSE;

	/** @var bool */
	private $enableLightbox = FALSE;


	/**
	 * @param \Nette\DI\Container|\SystemContainer $context
	 */
	public function __construct(\Nette\DI\Container $context)
	{
		$this->context = $context;
		$this->basePath = $context->parameters['basePath'];
	}


	/**
	 * @param boolean $enableLightbox
	 */
	public function setEnableLightbox($enableLightbox)
	{
		$this->enableLightbox = $enableLightbox;
	}


	/**
	 * @param boolean $enableThumbnails
	 */
	public function setEnableThumbnails($enableThumbnails)
	{
		$this->enableThumbnails = $enableThumbnails;
	}


	/**
	 * Array of events.
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array(
			ContentEditorEvents::onContentEditorLoad,
			ContentEditorEvents::onContentEditorSave,
			ContentEditorEvents::onContentEditorRender,
		);
	}


	public function onContentEditorLoad(ContentEditorArgs $args)
	{
		$value = $args->getValue();

		$value = preg_replace(array_keys($this->getPatternsLoad()), array_merge($this->getPatternsLoad()), $value);

		$args->setValue($value);
	}


	public function onContentEditorSave(ContentEditorArgs $args)
	{
		$value = $args->getValue();

		$value = preg_replace(array_keys($this->getPatternsSave()), array_merge($this->getPatternsSave()), $value);

		$args->setValue($value);
	}


	public function onContentEditorRender(ContentEditorArgs $args)
	{
		$value = $args->getValue();
		$presenter = $this->context->application->getPresenter();

		$snippetMode = $presenter->snippetMode;
		$presenter->snippetMode = NULL;
		$template = $presenter->createTemplate('Nette\Templating\Template');
		$template->setSource($value);

		$args->setValue($template->__toString());
		$presenter->snippetMode = $snippetMode;
	}


	/**
	 * @return array
	 */
	protected function  getPatternsLoad()
	{
		$ret = array();

		if ($this->enableThumbnails) {
			if ($this->enableLightbox) {
				$ret['/<a href="{\$basePath}\/public\/media\/([^"]*)" rel="lightbox"><img([^>]*)n:src="\1([^"]*)"([^>]*)><\/a>/'] = '<img${2}n:src="${1}${3}"${4}>';
			}

			$ret['/(<img[^>]*)n:src="([^ "]*)[ ]*,[ ]*size=>\'([^x]*)x([^\']*)\'[^"]*"/'] = '${1}src="{$basePath}/public/media/${2}" style="width: ${3}px; height: ${4}px;"';
		}

		$ret['/(<a[^>]*)n:fhref="([^ "]*)"/'] = '${1}href="{$basePath}/public/media/${2}"';
		$ret['/src="{\$basePath}\//'] = 'src="' . $this->basePath . '/';
		$ret['/href="{\$basePath}\//'] = 'href="' . $this->basePath . '/';

		return $ret;
	}


	/**
	 * @return array
	 */
	protected function  getPatternsSave()
	{
		$ret = array(
			'/src="' . str_replace("/", "\/", $this->basePath) . '\//' => 'src="{$basePath}/',
			'/href="' . str_replace("/", "\/", $this->basePath) . '\//' => 'href="{$basePath}/',
			'/(<a[^>]*)href="\{\$basePath\}\/public\/media\/([^"]*)"/' => '${1}n:fhref="${2}"',
		);

		if ($this->enableThumbnails) {
			$ret['/(<img[^>]*)src="\{\$basePath\}\/public\/media\/([^"]*)"([ ]*)style="([ ]*(?:width:[ ]*(\d+)px;[ ]*)*(?:height:[ ]*(\d+)px;[ ]*)*(?:width:[ ]*(\d+)px[;]*[ ]*)*)"[ ]*\/>/'] = '${1}n:src="${2}, size=>\'${5}${7}x${6}\', format=>\Nette\Image::STRETCH" style="${4}" />';

			if ($this->enableLightbox) {
				$ret['/<img([^>]*)n:src="([^,]+)([^"]*"[^>]*\/>)/'] = '<a href="{$basePath}/public/media/${2}" rel="lightbox"><img${1}n:src="${2}${3}</a>';
			}
		}

		return $ret;
	}
}
