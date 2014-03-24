<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace PaymentsModule;

use Nette\Forms\Container;
use PaymentsModule\Admin\Payments\AccountEntity;
use PaymentsModule\Admin\Payments\PaymentEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
interface IDriver
{

	/**
	 * @return string
	 */
	public function getName();


	/**
	 * @return string
	 */
	public function getCode();


	/**
	 * @param AccountEntity $account
	 * @param \DateTime $dateFrom
	 * @param \DateTime $dateTo
	 * @return PaymentEntity[]
	 */
	public function getPayments(AccountEntity $account, \DateTime $dateFrom = NULL, \DateTime $dateTo = NULL);


	/**
	 * @param Container $container
	 * @return Container
	 */
	public function configureOptionsContainer(Container $container);

}
