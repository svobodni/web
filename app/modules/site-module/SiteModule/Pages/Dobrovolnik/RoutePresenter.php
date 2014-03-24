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

use BlogModule\Pages\Blog\AbstractRoutePresenter;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class RoutePresenter extends AbstractRoutePresenter
{

	/** @var DobrovolnikRepository */
	private $repository;

	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var VillageRepository */
	private $villageRepository;

	/** @var DobrovolnikFormFactory */
	private $formFactory;


	/**
	 * @param DobrovolnikRepository $repository
	 * @param CategoryRepository $categoryRepository
	 * @param VillageRepository $villageRepository
	 * @param DobrovolnikFormFactory $formFactory
	 */
	public function inject(
		DobrovolnikRepository $repository,
		CategoryRepository $categoryRepository,
		VillageRepository $villageRepository,
		DobrovolnikFormFactory $formFactory
	)
	{
		$this->repository = $repository;
		$this->categoryRepository = $categoryRepository;
		$this->villageRepository = $villageRepository;
		$this->formFactory = $formFactory;
	}




	/**
	 * @return \SiteModule\Pages\Dobrovolnik\VillageRepository
	 */
	public function getVillageRepository()
	{
		return $this->villageRepository;
	}



	/**
	 * @return DobrovolnikRepository
	 */
	protected function getRepository()
	{
		return $this->repository;
	}


	/**
	 * @return CategoryRepository
	 */
	public function getCategoryRepository()
	{
		return $this->categoryRepository;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->invoke(new DobrovolnikEntity($this->extendedPage));
		$form->onSuccess[] = $this->formSuccess;
		$form->onError[] = $this->formError;
		return $form;
	}


	public function formSuccess(Form $form)
	{
		$this->flashMessage('Děkujeme za Vaši ochotu a čas a těšíme se na spolupráci. Do 14 dnů Vás bude kontaktovat člen volebního štábu.', 'success');
		$this->redirect('this');
	}


	public function formError(Form $form)
	{
		$this->flashMessage('Něco proběhlo špatně', 'warning');
		$this->redirect('this');
	}


	public function handleRemove($id)
	{
		if (!$this->user->isLoggedIn()) {
			throw new ForbiddenRequestException;
		}

		if (!$entity = $this->getRepository()->find($id)) {
			throw new BadRequestException;
		}

		$this->getRepository()->delete($entity);
		$this->flashMessage('Uživatel byl odstraněn', 'success');
		$this->redirect('this');
	}

}
