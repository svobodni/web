<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace EventsModule\Pages\Events;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractRoutePresenter extends \BlogModule\Pages\Blog\AbstractRoutePresenter
{

	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getQueryBuilder()
	{
		$date = new \DateTime;
		$date->setTime(0, 0, 0);

		return parent::getQueryBuilder()
			->andWhere('a.date >= :now')->setParameter('now', $date);
	}

}