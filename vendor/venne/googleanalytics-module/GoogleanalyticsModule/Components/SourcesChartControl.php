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
class SourcesChartControl extends BaseChartControl
{

	/** @var string */
	protected $metrics = 'ga:visits';


	public function render()
	{
		call_user_func_array(array($this, 'setArguments'), func_get_args());
		call_user_func_array(array('parent', 'render'), func_get_args());
	}


	protected function getGoogleAnalyticsData()
	{
		$data = $this->getGoogleAnalyticsService()->data_ga->get(
			'ga:' . $this->analyticsManager->getGaId(),
			$this->date[1]->format('Y-m-d'),
			$this->date[0]->format('Y-m-d'),
			$this->metrics,
			array(
				'dimensions' => 'ga:source',
				'max-results' => '7',
				'sort' => '-ga:visits',
			)
		);

		$ret = array();
		foreach ($data->getRows() as $row) {
			if (!isset($ret[$row[0]])) {
				$ret[$row[0]] = $row[1];
			} else {
				$ret[$row[0]] += $row[1];
			}
		}

		return $ret;
	}
}
