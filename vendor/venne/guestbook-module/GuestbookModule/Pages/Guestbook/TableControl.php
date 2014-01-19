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

use CmsModule\Content\Components\RouteItemsControl;
use CmsModule\Content\SectionControl;
use Grido\DataSources\Doctrine;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class TableControl extends SectionControl
{

	/** @var CommentRepository */
	protected $commentRepository;

	/** @var CommentFormFactory */
	protected $commentFormFactory;


	/**
	 * @param CommentRepository $commentRepository
	 * @param CommentFormFactory $commentFormFactory
	 */
	public function __construct(CommentRepository $commentRepository, CommentFormFactory $commentFormFactory)
	{
		parent::__construct();

		$this->commentRepository = $commentRepository;
		$this->commentFormFactory = $commentFormFactory;
	}


	protected function createComponentTable()
	{
		$adminControl = new RouteItemsControl($this->commentRepository, $this->getExtendedPage());
		$admin = $adminControl->getTable();
		$table = $admin->getTable();
		$table->setModel(new Doctrine($this->commentRepository->createQueryBuilder('a')
				->andWhere('a.extendedPage = :page')
				->setParameter('page', $this->extendedPage->id)
		));

		$repository = $this->commentRepository;
		$entity = $this->extendedPage;
		$form = $admin->createForm($this->commentFormFactory, 'Comment', function () use ($repository, $entity) {
			return $repository->createNew(array($entity));
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
