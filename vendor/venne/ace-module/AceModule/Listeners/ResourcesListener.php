<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace AceModule\Listeners;

use AssetsModule\CssFileCollection;
use AssetsModule\JsFileCollection;
use CmsModule\Administration\AdminPresenter;
use Doctrine\Common\EventSubscriber;
use CmsModule\Events\RenderEvents;
use Venne\Module\Helpers;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ResourcesListener implements EventSubscriber
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


	public function onHeadBegin(\CmsModule\Events\RenderArgs $args)
	{
		if ($args->getPresenter() instanceof AdminPresenter) {
			$this->jsFileCollection->addFile($this->wwwDir . '/' . $this->moduleHelpers->expandResource('@aceModule/ace/ace.js'));
			$this->jsFileCollection->addFile($this->wwwDir . '/' . $this->moduleHelpers->expandResource('@aceModule/code.js'));
		}
	}
}
