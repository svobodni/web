<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Pobocka;

use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormFactory extends \DoctrineModule\Forms\FormFactory
{

	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addGroup('Kontakty');
		$form->addText('city', 'Město');
		$form->addText('street', 'Ulice');
		$form->addText('zip', 'PSČ');
		$form->addText('phone', 'Telefon');
		$form->addText('email', 'E-mail');
		$form->addText('account', 'Číslo účtu');

		$form->addGroup('Sociální sítě');
		$form->addText('fb', 'Facebook');

		$form->addGroup('Lidé');
		$form->addManyToOne('coordinator', 'Koordinátor');
		$form->addManyToMany('members', 'Členové');
		$form->addManyToMany('supporters', 'Přiznivci');

		$form->setCurrentGroup();
		$form->addSaveButton('Save');
	}

}
