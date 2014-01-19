<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace MailformModule\Pages\Mailform;

use Venne\Forms\Form;
use DoctrineModule\Forms\FormFactory;
use MailformModule\Pages\Mailform\Forms\ControlExtensions\MailformExtension;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class MailformFormFactory extends FormFactory
{


	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new \CmsModule\Content\ControlExtension(),
			new \FormsModule\ControlExtensions\ControlExtension(),
			new MailformExtension(),
		));
	}


	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addMailform('mailform');

		$form->addSaveButton('Save');
	}
}
