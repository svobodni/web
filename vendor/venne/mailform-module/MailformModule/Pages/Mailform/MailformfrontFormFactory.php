<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace MailformModule\Pages\Mailform;

use Venne\Forms\Form;
use Venne\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class MailformfrontFormFactory extends FormFactory
{

	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new \FormsModule\ControlExtensions\ControlExtension(),
		));
	}


	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$container = $form->addContainer('_inputs');
		$container->setCurrentGroup($form->addGroup());

		$container->addText('_email', 'E-mail')
			->addRule($form::EMAIL)
			->setRequired(true);
		$container->addText('_name', 'Name')->setRequired(true);
		foreach ($form->data->inputs as $key => $input) {
			if ($input->getType() === InputEntity::TYPE_GROUP) {
				$container->setCurrentGroup($form->addGroup($input->getLabel()));
				continue;
			}

			$control = $container->add($input->getType(), 'input_' . $key, $input->getLabel());

			if ($input->required) {
				$control->setRequired(true);
			}

			if (in_array($input->getType(), array(InputEntity::TYPE_SELECT, InputEntity::TYPE_CHECKBOX_LIST, InputEntity::TYPE_RADIO_LIST))) {
				$items = array();
				foreach ($input->getItems() as $item) {
					$items[$item] = $item;
				}
				$control->setItems($items);
			}
		}

		$form->addGroup();
		$form->addSaveButton('Send');
	}
}
