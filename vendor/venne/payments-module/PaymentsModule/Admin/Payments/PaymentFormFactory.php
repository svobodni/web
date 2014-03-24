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

use DoctrineModule\Forms\FormFactory;
use FormsModule\ControlExtensions\ControlExtension;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PaymentFormFactory extends FormFactory
{

	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new ControlExtension,
		));
	}


	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addManyToOne('account', 'Account');
		$form->addManyToOne('offset', 'Offset');

		$form->addText('paymentId', 'Payment ID');
		$form->addDateTime('date', 'Date');
		$form->addText('amount', 'Amount');

		$form->addText('constantSymbol', 'Constant sb.');
		$form->addText('variableSymbol', 'Variable sb.');
		$form->addText('specificSymbol', 'Specific sb.');

		$form->addText('userIdentification', 'User identific.');
		$form->addTextArea('message', 'Message')
			->addRule($form::MAX_LENGTH, NULL, 140);

		$form->addText('type', 'Type');
		$form->addText('performed', 'Performed')
			->addRule($form::MAX_LENGTH, NULL, 50);

		$form->addText('specification', 'Specification');
		$form->addText('comment', 'Comment');
		$form->addText('bic', 'BIC');
		$form->addText('instructionId', 'Instruction ID');

		$form->addSaveButton('Save');
	}

}
