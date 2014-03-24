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
class BankTableFactory extends BaseFactory
{


	/** @var BankRepository */
	private $bankRepository;

	/** @var BankFormFactory */
	private $bankFormFactory;

	/** @var ITranslator */
	private $translator;


	/**
	 * @param BankRepository $bankRepository
	 * @param BankFormFactory $bankFormFactory
	 * @param ITranslator $translator
	 */
	public function __construct(BankRepository $bankRepository, BankFormFactory $bankFormFactory,  ITranslator $translator = NULL)
	{
		$this->bankRepository = $bankRepository;
		$this->bankFormFactory = $bankFormFactory;
		$this->translator = $translator;
	}


	public function invoke()
	{
		$admin = new AdminGrid($this->bankRepository);
		$table = $admin->getTable();
		$table->setTranslator($this->translator);

		$table->addColumnText('name', 'Name');
		$table->addColumnText('code', 'Code');

		$table->addAction('edit', 'Edit')
			->getElementPrototype()->class[] = 'ajax';

		$form = $admin->createForm($this->bankFormFactory, 'Bank', NULL, \CmsModule\Components\Table\Form::TYPE_FULL);

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

}
