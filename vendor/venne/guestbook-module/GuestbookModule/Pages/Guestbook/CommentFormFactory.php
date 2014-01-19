<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GuestbookModule\Pages\Guestbook;

use Venne\Forms\Form;
use DoctrineModule\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CommentFormFactory extends FormFactory
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
		$route = $form->addOne('route');

		$form->addGroup();
		$form->addManyToOne('parent', 'Parent');

		$group = $form->addGroup('Author');
		$authorName = $form->addText('author', 'Name');
		$route->setCurrentGroup($group);
		$author = $route->addManyToOne('author', 'Author');

		$authorName
			->addConditionOn($author, $form::FILLED)
			->addRule($form::EQUAL, '')
			->elseCondition()
			->addRule($form::FILLED);

		$author
			->addConditionOn($authorName, $form::FILLED)
			->addRule($form::EQUAL, '')
			->elseCondition()
			->addRule($form::FILLED);

		$form->addGroup('Text');
		$form->addTextArea('text', 'Text')
			->setRequired(TRUE)
			->getControlPrototype()->attrs['class'][] = 'span12';

		$form->addSaveButton('Save');
	}
}
