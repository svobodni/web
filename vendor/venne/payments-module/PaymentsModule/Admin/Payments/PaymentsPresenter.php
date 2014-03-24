<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace PaymentsModule\Admin\Payments;

use CmsModule\Administration\AdminPresenter;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @secured
 */
class PaymentsPresenter extends AdminPresenter
{

	/** @var PaymentsTableFactory */
	private $paymentTableFactory;

	/** @var AccountsTableFactory */
	private $accountsTableFactory;

	/** @var CurrencyTableFactory */
	private $currencyTableFactory;

	/** @var BankTableFactory */
	private $bankTableFactory;

	/** @var AccountFormFactory */
	private $accountFormFactory;


	/**
	 * @param PaymentsTableFactory $paymentTableFactory
	 * @param AccountsTableFactory $accountsTableFactory
	 * @param CurrencyTableFactory $currencyTableFactory
	 * @param BankTableFactory $bankTableFactory
	 * @param AccountFormFactory $accountFormFactory
	 */
	public function inject(
		PaymentsTableFactory $paymentTableFactory,
		AccountsTableFactory $accountsTableFactory,
		CurrencyTableFactory $currencyTableFactory,
		BankTableFactory $bankTableFactory,
		AccountFormFactory $accountFormFactory
	)
	{
		$this->paymentTableFactory = $paymentTableFactory;
		$this->accountsTableFactory = $accountsTableFactory;
		$this->currencyTableFactory = $currencyTableFactory;
		$this->bankTableFactory = $bankTableFactory;
		$this->accountFormFactory = $accountFormFactory;
	}


	protected function createComponentTable()
	{
		$control = $this->paymentTableFactory->invoke();
		return $control;
	}


	protected function createComponentAccountsTable()
	{
		$control = $this->accountsTableFactory->invoke();
		return $control;
	}


	protected function createComponentCurrencyTable()
	{
		$control = $this->currencyTableFactory->invoke();
		return $control;
	}


	protected function createComponentBankTable()
	{
		$control = $this->bankTableFactory->invoke();
		return $control;
	}

}
