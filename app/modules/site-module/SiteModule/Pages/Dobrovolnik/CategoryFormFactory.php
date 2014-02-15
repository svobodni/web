<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Dobrovolnik;

use DoctrineModule\Forms\FormFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CategoryFormFactory extends FormFactory
{

	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new \CmsModule\Content\Forms\ControlExtensions\ControlExtension,
		));
	}

	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$route = $form->addOne('route');

		$group = $form->addGroup();
		$form->addText('name', 'Name');
		$route->setCurrentGroup($group);
		$route->addTextArea('notation', 'Notation');
		$form->addManyToOne('parent', 'Parent');
		$route->addFileEntityInput('photo', 'Photo');

		$form->addGroup();
		$form->addSaveButton('Save');
	}


	public function handleAttached(Form $form)
	{
		$control = $form->lookup('\\CmsModule\\Content\\SectionControl');
		$form['parent']->setCriteria(array('extendedPage' => $control->extendedPage->id));
	}

}
