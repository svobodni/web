<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule\Components;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class VisitorsChartControl extends BaseChartControl
{

	/** @persistent */
	public $showVisits = TRUE;

	/** @persistent */
	public $showNewVisits = TRUE;

	/** @persistent */
	public $showPageViews = TRUE;

	/** @var string */
	protected $filterPath;

	/** @var string */
	protected $metrics = 'ga:newVisits,ga:visits,ga:pageviews';


	/**
	 * @param string $filterPath
	 */
	public function setFilterPath($filterPath)
	{
		$this->filterPath = $filterPath;
	}


	/**
	 * @return string
	 */
	public function getFilterPath()
	{
		return $this->filterPath;
	}


	public function setArguments()
	{
		$args = func_get_args();

		if (isset($args[0]['filterPath'])) {
			$this->filterPath = $args[0]['filterPath'];
		}

		call_user_func_array(array('parent', 'setArguments'), func_get_args());
	}


	protected function getGoogleAnalyticsData()
	{
		return $this->getGoogleAnalyticsService()->data_ga->get(
			'ga:' . $this->analyticsManager->getGaId(),
			$this->date[1]->format('Y-m-d'),
			$this->date[0]->format('Y-m-d'),
			$this->metrics,
			$this->getGoogleAnalyticsArgs()
		);
	}


	protected function getGoogleAnalyticsArgs()
	{
		$ret = array(
			'dimensions' => 'ga:date,ga:year,ga:month,ga:day',
			'max-results' => '31',
		);

		if ($this->filterPath) {
			$ret['filters'] = "ga:pagePath=~{$this->filterPath}/*";
		}

		return $ret;
	}


	public function render()
	{
		call_user_func_array(array($this, 'setArguments'), func_get_args());
		$this->metrics = array();

		if ($this->showNewVisits) {
			$this->metrics[] = 'ga:newVisits';
		}
		if ($this->showVisits) {
			$this->metrics[] = 'ga:visits';
		}
		if ($this->showPageViews) {
			$this->metrics[] = 'ga:pageviews';
		}
		$this->metrics = implode(',', $this->metrics);

		call_user_func_array(array('parent', 'render'), func_get_args());
	}


	public function renderChart($return = FALSE)
	{
		$this->metrics = array();

		if ($this->showNewVisits) {
			$this->metrics[] = 'ga:newVisits';
		}
		if ($this->showVisits) {
			$this->metrics[] = 'ga:visits';
		}
		if ($this->showPageViews) {
			$this->metrics[] = 'ga:pageviews';
		}
		$this->metrics = implode(',', $this->metrics);

		call_user_func_array(array('parent', 'renderChart'), func_get_args());
	}
}
