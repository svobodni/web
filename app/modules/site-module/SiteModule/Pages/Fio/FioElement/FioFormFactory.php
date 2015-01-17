<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Fio\FioElement;

use DoctrineModule\Forms\FormFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FioFormFactory extends FormFactory
{

	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addManyToOne('page', 'Stránka');
		$form->addText('itemsPerPage', 'Počet transakcí')
			->addRule($form::INTEGER);
		$form->addSaveButton('Save');
	}
}
