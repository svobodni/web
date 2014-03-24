<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DirectoryModule\Admin\Directory;

use DoctrineModule\Forms\FormFactory;
use FormsModule\ControlExtensions\ControlExtension;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PersonFormFactory extends FormFactory
{

	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new ControlExtension,
			new \CmsModule\Content\Forms\ControlExtensions\ControlExtension,
		));
	}


	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addGroup('Person');
		$form->addSelect('type', 'Person', PersonEntity::getTypes());
		$form->addText('name', 'Name');
		$form->addFileEntityInput('logo', 'Logo');
		$form->addManyToMany('users', 'Users');
		$form->addTextArea('description', 'Description');

		$form->addGroup('Address');
		$form->addText('street', 'Street');
		$form->addText('number', 'Number');
		$form->addText('city', 'City');
		$form->addText('zip', 'ZIP');

		$form->addGroup('Contacts');
		$form->addText('email', 'Email')
			->addCondition($form::FILLED)->addRule($form::EMAIL);
		$form->addText('phone', 'Phone');
		$form->addText('fax', 'Fax');
		$form->addText('website', 'Website')
			->addCondition($form::FILLED)->addRule($form::URL);

		$form->addGroup('Billing information');
		$form->addText('identificationNumber', 'IN');
		$form->addText('taxIdentificationNumber', 'TIN');
		$form->addText('registration', 'Registration');
		$form->addCheckbox('taxpayer', 'Taxpayer');
		$form->addFileEntityInput('signature', 'Signature');

		$form->addSaveButton('Save');
	}

}
