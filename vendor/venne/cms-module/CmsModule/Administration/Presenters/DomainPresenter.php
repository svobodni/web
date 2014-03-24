<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CmsModule\Administration\Presenters;

use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use CmsModule\Administration\Forms\DomainFormFactory;
use CmsModule\Content\Repositories\DomainRepository;
use CmsModule\Forms\LanguageFormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @secured
 */
class DomainPresenter extends BasePresenter
{

	/** @var DomainRepository */
	protected $domainRepository;

	/** @var DomainFormFactory */
	protected $domainFormFactory;


	/**
	 * @param DomainRepository $domainRepository
	 * @param DomainFormFactory $domainFormFactory
	 */
	public function inject(
		DomainRepository $domainRepository,
		DomainFormFactory $domainFormFactory
	) {
		$this->domainRepository = $domainRepository;
		$this->domainFormFactory = $domainFormFactory;
	}


	/**
	 * @secured(privilege="show")
	 */
	public function actionDefault()
	{
	}


	/**
	 * @secured
	 */
	public function actionCreate()
	{
	}


	/**
	 * @secured
	 */
	public function actionEdit()
	{
	}


	/**
	 * @secured
	 */
	public function actionRemove()
	{
	}


	protected function createComponentTable()
	{
		$_this = $this;
		$repository = $this->domainRepository;
		$admin = new AdminGrid($this->domainRepository);

		// columns
		$table = $admin->getTable();
		$table->setTranslator($this->translator);
		$table->addColumnText('name', 'Name')
			->setSortable()
			->getCellPrototype()->width = '40%';

		$table->addColumnText('domain', 'Domain')
			->setSortable()
			->getCellPrototype()->width = '30%';

		$table->addColumnText('page', 'Main page')
			->setSortable()
			->getCellPrototype()->width = '30%';

		// actions
		if ($this->isAuthorized('edit')) {
			$table->addAction('edit', 'Edit')
				->getElementPrototype()->class[] = 'ajax';

			$form = $admin->createForm($this->domainFormFactory, 'Language', NULL, \CmsModule\Components\Table\Form::TYPE_LARGE);
			$admin->connectFormWithAction($form, $table->getAction('edit'));

			// Toolbar
			$toolbar = $admin->getNavbar();
			$toolbar->addSection('new', 'Create', 'file');
			$admin->connectFormWithNavbar($form, $toolbar->getSection('new'));

			$admin->onAttached[] = function (AdminGrid $admin) use ($table, $_this, $repository) {
				if ($admin->formName && !$admin->id) {
					$admin['navbarForm']->onSuccess[] = function () use ($_this, $repository) {
						if ($repository->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult() <= 1) {
							$_this->redirect('this');
						}
					};
				}
			};
		}

		if ($this->isAuthorized('remove')) {
			$table->addAction('delete', 'Delete')
				->getElementPrototype()->class[] = 'ajax';
			$admin->connectActionAsDelete($table->getAction('delete'));

			$table->getAction('delete')->onClick[] = function () use ($_this, $repository) {
				if ($repository->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult() == 0) {
					$_this->redirect('this');
				}
			};
		}

		return $admin;
	}
}
