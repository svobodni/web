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

use DoctrineModule\Forms\FormFactory;
use DoctrineModule\Forms\Mappers\EntityMapper;
use Nette\Security\User;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CommentFrontFormFactory extends FormFactory
{

	/** @var User */
	protected $user;


	/**
	 * @param \DoctrineModule\Forms\Mappers\EntityMapper $mapper
	 * @param \Nette\Security\User $user
	 */
	public function __construct(EntityMapper $mapper, User $user)
	{
		parent::__construct($mapper);

		$this->user = $user;
	}


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
		$form->addProtection();
		$form->addAntispam();

		if (!$this->user->isLoggedIn()) {
			$form->addText('author', 'Name')->setRequired();
		}

		$form->addTextArea('text', 'Text')
			->setRequired(TRUE)
			->getControlPrototype()->class[] = 'input-block-level';

		$form->addSaveButton('Save');
	}


	public function handleSave(Form $form)
	{
		if ($this->user->isLoggedIn()) {
			$form->data->route->author = $this->user->identity;
		} else {
			$form->data->author = $form['author']->getValue();
		}

		parent::handleSave($form);
	}
}
