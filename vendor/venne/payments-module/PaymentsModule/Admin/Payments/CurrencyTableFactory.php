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
class CurrencyTableFactory extends BaseFactory
{


	/** @var CurrencyRepository */
	private $currencyRepository;

	/** @var CurrencyFormFactory */
	private $currencyFormFactory;

	/** @var ITranslator */
	private $translator;


	/**
	 * @param CurrencyRepository $currencyRepository
	 * @param CurrencyFormFactory $currencyFormFactory
	 * @param ITranslator $translator
	 */
	public function __construct(CurrencyRepository $currencyRepository, CurrencyFormFactory $currencyFormFactory, ITranslator $translator = NULL)
	{
		$this->currencyRepository = $currencyRepository;
		$this->currencyFormFactory = $currencyFormFactory;
		$this->translator = $translator;
	}


	public function invoke()
	{
		$admin = new AdminGrid($this->currencyRepository);
		$table = $admin->getTable();
		$table->setTranslator($this->translator);

		$table->addColumnText('name', 'Name');

		$table->addAction('edit', 'Edit')
			->getElementPrototype()->class[] = 'ajax';

		$form = $admin->createForm($this->currencyFormFactory, 'Payment', NULL, \CmsModule\Components\Table\Form::TYPE_FULL);

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
