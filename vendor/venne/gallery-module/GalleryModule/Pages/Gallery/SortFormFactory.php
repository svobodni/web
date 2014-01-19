<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GalleryModule\Pages\Gallery;

use Venne\Forms\Form;
use DoctrineModule\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class SortFormFactory extends FormFactory
{


	public function configure(Form $form)
	{
		$form->addHidden('sort');
		$form->addSaveButton('Store layout');
	}


	public function handleSave(Form $form)
	{
		/** @var $entity CategoryEntity */
		$entity = $form->data;
		$data = $form->getValues();
		$sort = json_decode($data['sort'], TRUE);

		foreach ($entity->getItems() as $photo) {
			if (($pos = array_search($photo->id, $sort)) !== false) {
				$photo->position = $pos;
			}
		}

		parent::handleSave($form);
	}
}
