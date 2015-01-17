<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Fio;

use DoctrineModule\Forms\Mappers\EntityMapper;
use SiteModule\Api\ApiClientFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormFactory extends \DoctrineModule\Forms\FormFactory
{

	public function configure(Form $form)
	{
		$form->addGroup('Nastavení API');
		$form->addText('accountNumber', 'Číslo účtu');

		$form->setCurrentGroup();
		$form->addSaveButton('Save');
	}

}
