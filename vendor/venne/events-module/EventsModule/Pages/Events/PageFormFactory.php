<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace EventsModule\Pages\Events;

use Venne\Forms\Form;
use DoctrineModule\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PageFormFactory extends FormFactory
{

	protected function getControlExtensions()
	{
		return array(
			new \DoctrineModule\Forms\ControlExtensions\DoctrineExtension(),
			new \CmsModule\Content\ControlExtension(),
			new \FormsModule\ControlExtensions\ControlExtension(),
		);
	}


	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addGroup('Settings');
		$form->addText('itemsPerPage', 'Items per page');

		$form->addGroup('Notation');
		$form->addCheckbox('notationInHtml', 'In HTML format');
		$form->addCheckbox('autoNotation', 'Auto generate')->addCondition($form::EQUAL, true)->toggle('notationLength');
		$form->addGroup()->setOption('id', 'notationLength');
		$form->addText('notationLength', 'Length')->addRule($form::INTEGER);

		$form->addGroup();
		$form->addSaveButton('Save');
	}
}
