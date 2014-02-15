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

use CmsModule\Content\Components\RouteItemsControl;
use CmsModule\Content\SectionControl;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class CategoriesControl extends SectionControl
{

	/** @var CategoryRepository */
	protected $categoryRepository;

	/** @var Callback */
	protected $categoryFormFactory;


	/**
	 * @param CategoryRepository $categoryRepository
	 * @param CategoryFormFactory $categoryFormFactory
	 */
	public function __construct(CategoryRepository $categoryRepository, CategoryFormFactory $categoryFormFactory)
	{
		parent::__construct();

		$this->categoryRepository = $categoryRepository;
		$this->categoryFormFactory = $categoryFormFactory;
	}


	protected function createComponentTable()
	{
		$adminControl = new RouteItemsControl($this->categoryRepository, $this->getExtendedPage());
		$admin = $adminControl->getTable();
		$table = $admin->getTable();


		$repository = $this->categoryRepository;
		$entity = $this->extendedPage;
		$form = $admin->createForm($this->categoryFormFactory, 'Category', function () use ($repository, $entity) {
			return $repository->createNew(array($entity));
		}, \CmsModule\Components\Table\Form::TYPE_LARGE);

		$admin->connectFormWithAction($form, $table->getAction('edit'));

		// Toolbar
		$toolbar = $admin->getNavbar();
		$toolbar->addSection('new', 'Create', 'file');
		$admin->connectFormWithNavbar($form, $toolbar->getSection('new'));

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
