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

use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use GoogleanalyticsModule\AnalyticsManager;
use Nette\Utils\Html;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ContentTableListener
{

	/** @var AnalyticsManager */
	protected $analyticsManager;


	public function __construct(AnalyticsManager $analyticsManager)
	{
		$this->analyticsManager = $analyticsManager;
	}


	public function onAttached(AdminGrid $table)
	{
		$table = $table->getTable();

		if ($this->analyticsManager->getApiActivated()) {
			$presenter = $table->getPresenter();


			$column = $table->addColumn('statistics', 'Statistics');
			$column->getCellPrototype()->width = '140';
			$column->setCustomRender(function ($entity) use ($presenter) {
					ob_start();
					$presenter['googleAnalyticsVisitorsMulti-' . $entity->id]->render(array(
						'size' => array(190, 51),
						'filterPath' => '/' . $entity->mainRoute->url,
						'options' => array(
							'pointSize' => '2',
							'hAxis' => array('textPosition' => 'none', 'gridlines' => array('color' => 'transparent')),
							'vAxis' => array('textPosition' => 'none', 'gridlines' => array('color' => 'transparent')),
						),
						'metrics' => 'ga:visits',
						'history' => '-2 weeks',
					));
					$ret = ob_get_clean();
					$html = Html::el('div');
					$html->style = 'margin: -15px -25px;';
					$html->setHtml($ret);
					return $html;
				});
		}
	}
}
