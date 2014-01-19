<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule\Listeners;

use CmsModule\Content\Presenters\PagePresenter;
use Doctrine\Common\EventSubscriber;
use CmsModule\Events\RenderEvents;
use GoogleanalyticsModule\AnalyticsManager;
use Venne\Widget\WidgetManager;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AnalyticsListener implements EventSubscriber
{

	/** @var AnalyticsManager */
	protected $analyticsManager;

	/** @var WidgetManager */
	protected $widgetManager;


	/**
	 * @param \GoogleanalyticsModule\AnalyticsManager $analyticsManager
	 * @param \Venne\Widget\WidgetManager $widgetManager
	 */
	public function __construct(AnalyticsManager $analyticsManager, WidgetManager $widgetManager)
	{
		$this->analyticsManager = $analyticsManager;
		$this->widgetManager = $widgetManager;
	}


	/**
	 * Array of events.
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array(RenderEvents::onHeadEnd);
	}


	public function onHeadEnd(\CmsModule\Events\RenderArgs $args)
	{
		if ($this->analyticsManager->getActivated() && $args->getPresenter() instanceof PagePresenter) {
			$control = $this->widgetManager->getWidget('googleAnalytics')->invoke();
			$control->render($this->analyticsManager->getAccountId());
		}
	}
}
