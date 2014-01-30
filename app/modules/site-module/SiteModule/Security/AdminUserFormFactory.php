<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Security;

use CmsModule\Pages\Users\BaseAdminFormFactory;
use FormsModule\ControlExtensions\ControlExtension;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AdminUserFormFactory extends BaseAdminFormFactory
{

	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new ControlExtension,
			new \CmsModule\Content\ControlExtension()
		));
	}


	protected function configure(Form $form)
	{
		parent::configure($form);

		$group = $form->addGroup('Další informace');
		$form->addText('address', 'Adresa');
		$form->addDateTime('birthDate', 'Datum narození');
		$form->addText('phone', 'Phone');
		$form->addText('website', 'Domovská stránka');
		$form->addText('facebook', 'Facebook');
		$form->addText('twitter', 'Twitter');
		$form->addText('linkedIn', 'LinkedIn');

		$route = $form['user']['route'];
		$route->setCurrentGroup($group);
		$route->addContentEditor('text');
	}

}
