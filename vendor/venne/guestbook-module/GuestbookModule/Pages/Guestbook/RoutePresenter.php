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

use CmsModule\Content\Presenters\ItemsPresenter;
use CmsModule\Pages\Users\UserEntity;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Nette\DateTime;
use Nette\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class RoutePresenter extends ItemsPresenter
{

	/** @persistent */
	public $id;

	/** @var CommentRepository */
	protected $repository;

	/** @var CommentFrontFormFactory */
	protected $commentFormFactory;


	/**
	 * @param \GuestbookModule\Repositories\CommentRepository $commentRepository
	 */
	public function injectRepository(CommentRepository $repository)
	{
		$this->repository = $repository;
	}


	/**
	 * @param \GuestbookModule\Forms\CommentFrontFormFactory $commentFormFactory
	 */
	public function injectCommentFormFactory(CommentFrontFormFactory $commentFormFactory)
	{
		$this->commentFormFactory = $commentFormFactory;
	}


	/**
	 * @return BaseRepository|CommentRepository
	 */
	protected function getRepository()
	{
		return $this->repository;
	}


	protected function getItemsPerPage()
	{
		return $this->extendedPage->itemsPerPage;
	}


	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getQueryBuilder()
	{
		return parent::getQueryBuilder()
			->andWhere('a.parent IS NULL');
	}


	public function handleReply()
	{
	}


	public function handleDelete()
	{
		if (($entity = $this->getCurrentComment()) === NULL) {
			throw new BadRequestException;
		}

		if (!$entity->route->author && !$this->user->isLoggedIn() && $entity->route->author->id !== $this->user->identity->id) {
			throw new ForbiddenRequestException;
		}

		$this->getRepository()->delete($entity);

		$this->flashMessage($this->translator->translate('Message has been deleted.'), 'success');
		$this->redirect('this', array('id' => NULL));
	}


	public function actionDefault()
	{
		if ($this->isLoggedInAsSuperadmin()) {
			$this->flashMessage($this->translator->translate('You are logged in as superadmin. You can not send new comments.'), 'info', TRUE);
		}
	}


	protected function createComponentForm()
	{
		if ($this->isLoggedInAsSuperadmin()) {
			throw new ForbiddenRequestException;
		}

		$entity = $this->getRepository()->createNew(array($this->extendedPage));
		if ($this->id) {
			$entity->setParent($this->getCurrentComment());
		}

		$form = $this->commentFormFactory->invoke($entity);

		if ($this->extendedPage->messageMaxLength) {
			$form['text']->addRule($form::MAX_LENGTH, 'Message is too long.', $this->extendedPage->messageMaxLength);
		}

		$form->onSuccess[] = $this->formSuccess;
		return $form;
	}


	public function formSuccess()
	{
		$this->flashMessage($this->translator->translate('Message has been saved.'), 'success');
		$this->redirect('this', array('id' => NULL));
	}


	/**
	 * @return bool
	 */
	public function isLoggedInAsSuperadmin()
	{
		return $this->user->isLoggedIn() && !$this->user->identity instanceof UserEntity;
	}


	/**
	 * @return CommentEntity
	 */
	public function getCurrentComment()
	{
		return $this->getRepository()->find($this->id);
	}
}