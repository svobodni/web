<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace LightboxModule\Listeners;

use AssetsModule\CssFileCollection;
use AssetsModule\JsFileCollection;
use CmsModule\Content\Presenters\PagePresenter;
use CmsModule\Events\RenderArgs;
use CmsModule\Events\RenderEvents;
use Doctrine\Common\EventSubscriber;
use Venne\Module\Helpers;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AssetsListener implements EventSubscriber
{

	/** @var JsFileCollection */
	private $jsFileCollection;

	/** @var CssFileCollection */
	private $cssFileCollection;

	/** @var Helpers */
	private $moduleHelpers;

	/** @var string */
	private $wwwDir;


	public function __construct($wwwDir, JsFileCollection $jsFileCollection, CssFileCollection $cssFileCollection, Helpers $moduleHelpers)
	{
		$this->wwwDir = $wwwDir;
		$this->jsFileCollection = $jsFileCollection;
		$this->cssFileCollection = $cssFileCollection;
		$this->moduleHelpers = $moduleHelpers;
	}


	/**
	 * Array of events.
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array(RenderEvents::onHeadBegin);
	}


	public function onHeadBegin(RenderArgs $args)
	{
		if($args->getPresenter() instanceof PagePresenter) {
			$this->jsFileCollection->addFile($this->wwwDir . '/' . $this->moduleHelpers->expandResource('@lightboxModule/fancybox/jquery.mousewheel-3.0.4.pack.js'));
			$this->jsFileCollection->addFile($this->wwwDir . '/' . $this->moduleHelpers->expandResource('@lightboxModule/fancybox/jquery.fancybox-1.3.4.pack.js'));
			$this->jsFileCollection->addFile($this->wwwDir . '/' . $this->moduleHelpers->expandResource('@lightboxModule/loader.js'));
			$this->cssFileCollection->addFile($this->wwwDir . '/' . $this->moduleHelpers->expandResource('@lightboxModule/fancybox/jquery.fancybox-1.3.4.css'));
		}
	}
}
