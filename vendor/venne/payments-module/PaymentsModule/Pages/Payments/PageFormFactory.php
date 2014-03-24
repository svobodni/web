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

use DoctrineModule\Forms\FormFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PageFormFactory extends FormFactory
{

	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addManyToMany('accounts', 'Accounts');
		$form->addText('itemsPerPage', 'Items per page')
			->addRule($form::FILLED)
			->addRule($form::NUMERIC);

		$form->setCurrentGroup();
		$form->addSaveButton('Save');
	}

}
