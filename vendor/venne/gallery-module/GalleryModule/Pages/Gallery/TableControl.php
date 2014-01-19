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

use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use CmsModule\Administration\Components\AjaxFileUploaderControl;
use CmsModule\Content\Components\RouteItemsControl;
use CmsModule\Content\SectionControl;
use Kdyby\Extension\Forms\BootstrapRenderer\BootstrapRenderer;
use Nette\Application\BadRequestException;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class TableControl extends SectionControl
{

	/** @persistent */
	public $key;

	/** @persistent */
	public $id;

	/** @var ItemRepository */
	protected $itemRepository;

	/** @var ProductFormFactory */
	protected $galleryFormFactory;

	/** @var UploadFormFactory */
	protected $uploadFormFactory;

	/** @var SortFormFactory */
	protected $sortFormFactory;

	/** @var PhotoFormFactory */
	protected $photoFormFactory;

	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var AjaxFileUploaderControlFactory */
	private $ajaxFileUploaderFactory;


	/**
	 * @param CategoryRepository $categoryRepository
	 * @param ItemRepository $itemRepository
	 * @param GalleryFormFactory $galleryFormFactory
	 * @param UploadFormFactory $uploadFormFactory
	 * @param SortFormFactory $sortFormFactory
	 * @param PhotoFormFactory $photoFormFactory
	 */
	public function __construct(CategoryRepository $categoryRepository, ItemRepository $itemRepository, GalleryFormFactory $galleryFormFactory, UploadFormFactory $uploadFormFactory, SortFormFactory $sortFormFactory, PhotoFormFactory $photoFormFactory, AjaxFileUploaderControlFactory $ajaxFileUploaderFactory)
	{
		parent::__construct();

		$this->categoryRepository = $categoryRepository;
		$this->itemRepository = $itemRepository;
		$this->galleryFormFactory = $galleryFormFactory;
		$this->uploadFormFactory = $uploadFormFactory;
		$this->sortFormFactory = $sortFormFactory;
		$this->photoFormFactory = $photoFormFactory;
		$this->ajaxFileUploaderFactory = $ajaxFileUploaderFactory;
	}


	/**
	 * @return RouteEntity
	 */
	public function getCategoryEntity()
	{
		return $this->key ? $this->categoryRepository->find($this->key) : NULL;
	}


	/**
	 * @return PhotoEntity
	 */
	public function getPhotoEntity()
	{
		return $this->id ? $this->itemRepository->find($this->id) : NULL;
	}


	public function handleEdit($id)
	{
		$this->id = $id;

		if (!$this->presenter->isAjax()) {
			$this->redirect('this', array('id' => $id));
		}

		$this->presenter->payload->url = $this->link('this', array('id' => $id));
		$this->invalidateControl('form');
	}


	public function handleDelete($id)
	{
		if (($entity = $this->itemRepository->find($id)) === NULL) {
			throw new BadRequestException;
		}

		$this->itemRepository->delete($entity);

		if (!$this->presenter->isAjax()) {
			$this->redirect('this', array('id' => NULL));
		}

		$this->presenter->payload->url = $this->link('this', array('id' => NULL));
		$this->invalidateControl('view');
	}


	protected function createComponentAjaxFileUploader()
	{
		$_this = $this;

		$this->ajaxFileUploaderFactory->setCategoryEntity($this->getCategoryEntity());

		/** @var AjaxFileUploaderControlFactory $control */
		$control = $this->ajaxFileUploaderFactory->invoke($this->template->basePath);
		$control->onSuccess[] = function () use ($_this) {
			$_this->presenter->invalidateControl('content');
		};
		$control->onError[] = function (AjaxFileUploaderControl $control) use ($_this) {
			foreach ($control->getErrors() as $e) {
				if ($e['class'] === 'Doctrine\DBAL\DBALException' && strpos($e['message'], 'Duplicate entry') !== false) {
					$_this->flashMessage('Duplicate entry', 'warning');
				} else {
					$_this->flashMessage($e['message']);
				}
			}
			$_this->presenter->invalidateControl('content');
		};
		return $control;
	}


	protected function createComponentTable()
	{
		$_this = $this;
		$adminControl = new RouteItemsControl($this->categoryRepository, $this->getExtendedPage());
		$admin = $adminControl->getTable();
		$table = $admin->getTable();


		$repository = $this->categoryRepository;
		$entity = $this->extendedPage;
		$form = $admin->createForm($this->galleryFormFactory, 'Category', function () use ($repository, $entity) {
			return $repository->createNew(array($entity));
		}, \CmsModule\Components\Table\Form::TYPE_LARGE);

		// Toolbar
		$toolbar = $admin->getNavbar();
		$toolbar->addSection('new', 'Create', 'file');
		$admin->connectFormWithNavbar($form, $toolbar->getSection('new'));

		$table->addAction('delete', 'Delete')
			->getElementPrototype()->class[] = 'ajax';
		$admin->connectActionAsDelete($table->getAction('delete'));

		$table->getAction('edit')
			->setCustomHref(function ($entity) use ($_this) {
				return $_this->link('this', array('key' => $entity->id));
			});

		return $adminControl;
	}


	protected function createComponentGalleryForm()
	{
		$form = $this->galleryFormFactory->invoke($this->getCategoryEntity());
		$form->onSuccess[] = $this->galleryFormSuccess;
		return $form;
	}


	protected function createComponentUploadForm()
	{
		$form = $this->uploadFormFactory->invoke($this->getCategoryEntity());
		$form->onSuccess[] = $this->uploadFormSuccess;
		return $form;
	}


	protected function createComponentSortForm()
	{
		$form = $this->sortFormFactory->invoke($this->getCategoryEntity());
		$form->onSuccess[] = $this->sortFormSuccess;
		return $form;
	}


	protected function createComponentPhotoForm()
	{
		$form = $this->photoFormFactory->invoke($this->getPhotoEntity());
		$form->onSuccess[] = $this->photoFormSuccess;
		$form->getSaveButton()->getControlPrototype()->onClick = '_a = window.alert; window.alert = function() {}; if(Nette.validateForm(this)) { $(this).parents(".modal").each(function(){ $(this).modal("hide"); }); } window.alert = _a';
		return $form;
	}


	public function uploadFormSuccess($form)
	{
		$this->redirect('this');
	}


	public function galleryFormSuccess($form)
	{
		$this->flashMessage('Gallery has been saved.', 'success');

		if (!$this->presenter->isAjax()) {
			$this->redirect('this');
		}

		$this->invalidateControl('view');
	}


	public function sortFormSuccess($form)
	{
		$this->flashMessage('Layout has been stored.', 'success');

		if (!$this->presenter->isAjax()) {
			$this->redirect('this');
		}

		$this->invalidateControl('view');
	}


	public function photoFormSuccess($form)
	{
		$this->presenter->flashMessage('Photo has been updated.', 'success');

		if (!$this->presenter->isAjax()) {
			$this->redirect('this', array('id' => NULL));
		}

		$this->presenter->payload->url = $this->link('this', array('id' => NULL));
		$this->invalidateControl('form');
		$this->id = NULL;
	}


	public function render()
	{
		$this->template->render();
	}
}
