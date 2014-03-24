<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace PaymentsModule\Pages\Payments;

use CmsModule\Content\Presenters\ItemsPresenter;
use DoctrineModule\Repositories\BaseRepository;
use PaymentsModule\Admin\Payments\PaymentRepository;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class RoutePresenter extends ItemsPresenter
{

	/** @var PaymentRepository */
	private $paymentRepository;


	/**
	 * @param PaymentRepository $paymentRepository
	 */
	public function inject(PaymentRepository $paymentRepository)
	{
		$this->paymentRepository = $paymentRepository;
	}


	/**
	 * @return BaseRepository|PaymentRepository
	 */
	protected function getRepository()
	{
		return $this->paymentRepository;
	}


	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getQueryBuilder()
	{
		$qb = $this->paymentRepository->createQueryBuilder('a');

		if (count($this->extendedPage->accounts)) {
			$ids = array();
			foreach ($this->extendedPage->accounts as $account) {
				$ids[] = $account->id;
			}

			$qb->andWhere('a.id IN (:accounts)')->setParameter('accounts', $ids);
		}

		return $qb;
	}

}
