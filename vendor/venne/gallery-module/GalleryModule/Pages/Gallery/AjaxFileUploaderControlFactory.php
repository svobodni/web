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

use CmsModule\Administration\Components\AbstractAjaxFileUploaderControlFactory;
use CmsModule\Administration\Components\AjaxFileUploaderControl;
use CmsModule\Content\Repositories\DirRepository;
use CmsModule\Content\Repositories\FileRepository;
use Nette\Http\Session;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AjaxFileUploaderControlFactory extends AbstractAjaxFileUploaderControlFactory
{

	/** @var AbstractCategoryEntity */
	private $categoryEntity;

	/** @var ItemRepository */
	private $itemRepository;


	public function __construct($ajaxDir, $wwwDir, DirRepository $dirRepository, FileRepository $fileRepository, Session $session, ItemRepository $itemRepository)
	{
		parent::__construct($ajaxDir, $wwwDir, $dirRepository, $fileRepository, $session);

		$this->itemRepository = $itemRepository;
	}


	/**
	 * @param $categoryEntity
	 */
	public function setCategoryEntity(AbstractCategoryEntity $categoryEntity)
	{
		$this->categoryEntity = $categoryEntity;
	}


	/**
	 * @param AjaxFileUploaderControl $control
	 * @param FileUpload $file
	 */
	public function handleFileUpload(AjaxFileUploaderControl $control, $fileName)
	{
		/** @var FileEntity $fileEntity */
		$fileEntity = $this->fileRepository->createNew();
		$fileEntity->setFile(new \SplFileInfo($this->ajaxDir . '/' . $fileName));
		$this->fileRepository->save($fileEntity);

		$this->categoryEntity->items[] = $photoEntity = new ItemEntity($this->categoryEntity->extendedPage);
		$photoEntity->route->setPhoto($fileEntity);
		$photoEntity->route->setParent($this->categoryEntity->route);
		$photoEntity->setCategory($this->categoryEntity);

		$this->itemRepository->save($photoEntity);
	}
}
