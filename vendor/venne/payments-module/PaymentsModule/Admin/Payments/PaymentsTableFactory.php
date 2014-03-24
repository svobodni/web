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
class PaymentsTableFactory extends BaseFactory
{


	/** @var PaymentRepository */
	private $paymentRepository;

	/** @var PaymentFormFactory */
	private $paymentFormFactory;

	/** @var PaymentManager */
	private $paymentManager;

	/** @var ITranslator */
	private $translator;


	/**
	 * @param PaymentRepository $paymentRepository
	 * @param PaymentFormFactory $paymentFormFactory
	 * @param PaymentManager $paymentManager
	 * @param ITranslator $translator
	 */
	public function __construct(PaymentRepository $paymentRepository, PaymentFormFactory $paymentFormFactory, PaymentManager $paymentManager, ITranslator $translator = NULL)
	{
		$this->paymentRepository = $paymentRepository;
		$this->paymentFormFactory = $paymentFormFactory;
		$this->paymentManager = $paymentManager;
		$this->translator = $translator;
	}


	public function invoke()
	{
		$admin = new AdminGrid($this->paymentRepository);
		$table = $admin->getTable();
		$table->setTranslator($this->translator);
		$table->setDefaultSort(array('date' => 'DESC'));

		$table->addColumnDate('date', 'Date');
		$table->addColumnText('offset', 'Offset');
		$table->addColumnText('amount', 'Amount');
		$table->addColumnText('constantSymbol', 'CS');
		$table->addColumnText('variableSymbol', 'VS');
		$table->addColumnText('specificSymbol', 'SS');
		$table->addColumnText('userIdentification', 'User identification');

		$table->addAction('edit', 'Edit')
			->getElementPrototype()->class[] = 'ajax';

		$form = $admin->createForm($this->paymentFormFactory, 'Payment', NULL, \CmsModule\Components\Table\Form::TYPE_FULL);

		$admin->connectFormWithAction($form, $table->getAction('edit'), $admin::MODE_PLACE);

		// Toolbar
		$toolbar = $admin->getNavbar();
		$toolbar->addSection('new', 'Create', 'file');
		$admin->connectFormWithNavbar($form, $toolbar->getSection('new'), $admin::MODE_PLACE);

		$toolbar->addSection('synchronize', 'Synchronize')
			->setIcon('refresh');
		$toolbar->getSection('synchronize')
			->onClick[] = $this->synchronizeClick;


		$table->addAction('delete', 'Delete')
			->getElementPrototype()->class[] = 'ajax';
		$admin->connectActionAsDelete($table->getAction('delete'));

		return $admin;
	}


	public function synchronizeClick()
	{
		$this->paymentManager->synchronizeAll();
	}

}
