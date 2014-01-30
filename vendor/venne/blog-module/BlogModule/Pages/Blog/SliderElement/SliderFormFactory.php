<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace BlogModule\Pages\Blog\SliderElement;

use BlogModule\Pages\Blog\BlogElement\BlogFormFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class SliderFormFactory extends BlogFormFactory
{

	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addGroup();
		$form->addText('itemsPerPage', 'Items per page');
		$form->addManyToMany('pages', 'Pages');
		$form->addManyToMany('categories', 'Categories');

		$form->addGroup('Dimensions');
		$form->addText('width', 'Width')->addCondition($form::FILLED)->addRule($form::INTEGER);
		$form->addText('height', 'Height')->addCondition($form::FILLED)->addRule($form::INTEGER);

		$form->addGroup();
		$form->addSaveButton('Save');
	}
}
