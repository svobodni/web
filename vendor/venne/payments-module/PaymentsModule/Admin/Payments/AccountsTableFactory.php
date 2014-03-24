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

use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use Nette\Localization\ITranslator;
use PaymentsModule\PaymentManager;
use Venne\BaseFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AccountsTableFactory extends BaseFactory
{


	/** @var AccountRepository */
	private $accountRepository;

	/** @var PaymentRepository */
	private $paymentRepository;

	/** @var AccountFormFactory */
	private $accountFormFactory;

	/** @var PaymentManager */
	private $paymentManager;

	/** @var ITranslator */
	private $translator;


	/**
	 * @param AccountRepository $accountRepository
	 * @param PaymentRepository $paymentRepository
	 * @param AccountFormFactory $accountFormFactory
	 * @param PaymentManager $paymentManager
	 * @param ITranslator $translator
	 */
	public function __construct(
		AccountRepository $accountRepository,
		PaymentRepository $paymentRepository,
		AccountFormFactory $accountFormFactory,
		PaymentManager $paymentManager,
		ITranslator $translator = NULL
	)
	{
		$this->accountRepository = $accountRepository;
		$this->paymentRepository = $paymentRepository;
		$this->accountFormFactory = $accountFormFactory;
		$this->paymentManager = $paymentManager;
		$this->translator = $translator;
	}


	public function invoke()
	{
		$admin = new AdminGrid($this->accountRepository);
		$table = $admin->getTable();
		$table->setTranslator($this->translator);

		$table->addColumnText('name', 'Account number')
			->getCellPrototype()->width = '40%';

		$table->addColumnText('bank', 'Bank')
			->setCustomRender(function(AccountEntity $account) {
				return (string) $account->bank;
			})
			->getCellPrototype()->width = '30%';

		$table->addColumnText('person', 'Person')
			->setCustomRender(function(AccountEntity $account){
				return $account->getPerson() ? $account->getPerson() : '';
			})
			->getCellPrototype()->width = '30%';

		$table->addAction('synchronize', 'Synchronize')
			->onClick[] = $this->synchronizeClick;

		$table->addAction('edit', 'Edit')
			->getElementPrototype()->class[] = 'ajax';

		$form = $admin->createForm($this->accountFormFactory, 'Account', NULL, \CmsModule\Components\Table\Form::TYPE_FULL);

		$admin->connectFormWithAction($form, $table->getAction('edit'), $admin::MODE_PLACE);

		// Toolbar
		$toolbar = $admin->getNavbar();
		$toolbar->addSection('new', 'Create', 'file');
		$admin->connectFormWithNavbar($form, $toolbar->getSection('new'), $admin::MODE_PLACE);

		$table->addAction('delete', 'Delete')
			->getElementPrototype()->class[] = 'ajax';
		$admin->connectActionAsDelete($table->getAction('delete'));

		return $admin;
	}


	public function synchronizeClick($button, $id)
	{
		/** @var AccountEntity $account */
		$account = $this->accountRepository->find($id);
		$this->paymentManager->synchronizeAccount($account);
	}

}
