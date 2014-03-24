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

use Nette\Object;
use PaymentsModule\Admin\Payments\AccountEntity;
use PaymentsModule\Admin\Payments\AccountRepository;
use PaymentsModule\Admin\Payments\PaymentEntity;
use PaymentsModule\Admin\Payments\PaymentRepository;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PaymentManager extends Object
{

	/** @var IDriver[] */
	private $drivers = array();

	/** @var PaymentRepository */
	private $paymentRepository;

	/** @var AccountRepository */
	private $accountRepository;


	/**
	 * @param PaymentRepository $paymentRepository
	 * @param AccountRepository $accountRepository
	 */
	public function __construct(PaymentRepository $paymentRepository, AccountRepository $accountRepository)
	{
		$this->paymentRepository = $paymentRepository;
		$this->accountRepository = $accountRepository;
	}


	/**
	 * @param IDriver $driver
	 */
	public function addDriver(IDriver $driver)
	{
		$this->drivers[] = $driver;
	}


	/**
	 * @return IDriver[]
	 */
	public function getDrivers()
	{
		return $this->drivers;
	}


	public function synchronizeAll()
	{
		foreach ($this->accountRepository->findAll() as $account) {
			$this->synchronizeAccount($account);
		}
	}


	/**
	 * @param AccountEntity $account
	 * @return bool
	 */
	public function synchronizeAccount(AccountEntity $account)
	{
		foreach ($this->getDrivers() as $driver) {
			if ($driver->getCode() == $account->getBank()->getCode()) {
				$payments = $driver->getPayments($account, $account->getSyncDate());
				$last = NULL;

				foreach ($payments as $payment) {
					if ($this->paymentRepository->findOneBy(array('paymentId' => $payment->getPaymentId())) || $this->paymentRepository->findOneBy(array('instructionId' => $payment->getInstructionId()))) {
						continue;
					}

					$payment->setAccount($account);
					$this->paymentRepository->save($payment);
					$last = $payment;
				}

				if ($last instanceof PaymentEntity) {
					$account->setSyncDate($last->getDate());
					$this->accountRepository->save($account);
				}

				return TRUE;
			}
		}

		return FALSE;
	}

}
