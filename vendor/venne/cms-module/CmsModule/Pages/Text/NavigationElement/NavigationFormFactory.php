<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CmsModule\Pages\Text\NavigationElement;

use DoctrineModule\Forms\FormFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class NavigationFormFactory extends FormFactory
{

	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addText('startDepth', 'Start depth')
			->addCondition($form::FILLED)
			->addRule($form::INTEGER);

		$form->addText('maxDepth', 'Max depth')
			->addCondition($form::FILLED)
			->addRule($form::INTEGER);

		$form->addManyToOne('root', 'Root page');

		$form->addSaveButton('Save');
	}

}
