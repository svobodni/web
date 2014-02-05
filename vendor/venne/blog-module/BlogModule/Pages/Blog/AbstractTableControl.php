<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace BlogModule\Pages\Blog;

use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use CmsModule\Content\Components\RouteItemsControl;
use CmsModule\Content\SectionControl;
use CmsModule\Pages\Users\UserEntity;
use DoctrineModule\Repositories\BaseRepository;
use Grido\DataSources\Doctrine;
use Nette\InvalidArgumentException;
use Venne\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractTableControl extends SectionControl
{

	protected function getRepository()
	{
	}


	protected function getFormFactory()
	{
	}


	protected function createComponentTable()
	{
		$_this = $this;
		$repository = $this->getRepository();
		$formFactory = $this->getFormFactory();

		if (!$repository instanceof BaseRepository) {
			throw new InvalidArgumentException("Method 'getRepository' must return repository.");
		}

		if (!$formFactory instanceof FormFactory) {
			throw new InvalidArgumentException("Method 'getFormFactory' must return formFactory.");
		}

		$adminControl = new RouteItemsControl($repository, $this->getExtendedPage());
		$admin = $adminControl->getTable();
		$table = $admin->getTable();

		$entity = $this->extendedPage;
		$form = $admin->createForm($formFactory, '', function () use ($repository, $entity, $_this) {
			$entity = $repository->createNew(array($entity));
			if ($_this->presenter->user->identity instanceof UserEntity) {
				$entity->route->author = $_this->presenter->user->identity;
			}
			return $entity;
		}, \CmsModule\Components\Table\Form::TYPE_FULL);

		$admin->connectFormWithAction($form, $table->getAction('edit'), $admin::MODE_PLACE);

		// Toolbar
		$toolbar = $admin->getNavbar();
		$toolbar->addSection('new', 'Create', 'file');
		$admin->connectFormWithNavbar($form, $toolbar->getSection('new'), $admin::MODE_PLACE);

		$table->addAction('delete', 'Delete')
			->getElementPrototype()->class[] = 'ajax';
		$admin->connectActionAsDelete($table->getAction('delete'));

		return $adminControl;
	}


	public function render()
	{
		$this->template->render();
	}
}
