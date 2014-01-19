<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CkeditorModule\Forms;

use FormsModule\Mappers\ConfigMapper;
use Venne\Forms\FormFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ContentFormFactory extends FormFactory
{

	/** @var ConfigMapper */
	protected $mapper;


	/**
	 * @param ConfigMapper $mapper
	 */
	public function __construct(ConfigMapper $mapper)
	{
		$this->mapper = $mapper;
	}


	protected function getMapper()
	{
		$mapper = clone $this->mapper;
		$mapper->setRoot('ckeditor');
		return $mapper;
	}


	/**
	 * @param Form $form
	 */
	protected function configure(Form $form)
	{
		$form->addGroup('Content');
		$form->addCheckbox('autoThumbnails', 'Auto thumbnails')
			->setDefaultValue(TRUE)
			->addCondition($form::EQUAL, TRUE)->toggle('form-lightbox');

		$form->addGroup()->setOption('id', 'form-lightbox');
		$form->addCheckbox('autoLightbox', 'Auto lightbox')
			->setDefaultValue(TRUE);

		$form->addGroup();
		$form->addSaveButton('Save');
	}

}
