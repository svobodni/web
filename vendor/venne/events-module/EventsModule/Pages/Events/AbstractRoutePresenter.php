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

	/** @persistent */
	public $archive = FALSE;

	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getQueryBuilder()
	{
		$date = new \DateTime;
		$date->setTime(0, 0, 0);

		$qb = parent::getQueryBuilder();

		if (!$this->archive) {
			$qb->andWhere('a.date >= :now')->setParameter('now', $date);
		} else {
			$qb->andWhere('a.date < :now')->setParameter('now', $date);
		}

		return $qb;
	}

}
