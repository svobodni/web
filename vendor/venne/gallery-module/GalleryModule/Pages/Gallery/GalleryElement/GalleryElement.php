<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GalleryModule\Pages\Gallery\GalleryElement;

use CmsModule\Content\Elements\BaseElement;
use GalleryModule\Pages\Gallery\CategoryRepository;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class GalleryElement extends BaseElement
{

	/** @var CategoryRepository */
	private $categoryRepository;

	/** @var GalleryFormFactory */
	protected $setupFormFactory;


	/**
	 * @param GalleryFormFactory $setupForm
	 */
	public function injectSetupForm(GalleryFormFactory $setupForm)
	{
		$this->setupFormFactory = $setupForm;
	}


	/**
	 * @param CategoryRepository $categoryRepository
	 */
	public function injectCategoryRepository(CategoryRepository $categoryRepository)
	{
		$this->categoryRepository = $categoryRepository;
	}


	/**
	 * @return array
	 */
	public function getViews()
	{
		return array(
			'setup' => 'Edit element',
		) + parent::getViews();
	}


	public function getItem()
	{
		if (!$this->extendedElement->page) {
			return NULL;
		}

		$query = $this->getQueryBuilder()
			->join('a.route', 'r')
			->andWhere('r.published = :true')->setParameter('true', TRUE)
			->andWhere('r.released <= :now')
			->andWhere('r.expired IS NULL OR r.expired > :now')->setParameter('now', new \DateTime)
			->orderBy('r.released', 'DESC')
			->getQuery();

		return $query->getOneOrNullResult();
	}


	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getQueryBuilder()
	{
		$dql = $this->categoryRepository->createQueryBuilder('a')
				->join('a.extendedPage', 'p')
				->andWhere('p.linkedPage = :page')->setParameter('page', $this->presenter->page->id)
				->andWhere('a.linkedRoute = :route')->setParameter('route', $this->presenter->route->id);

		return $dql;
	}


	public function renderSetup()
	{
		echo $this['form']->render();
	}


	/**
	 * @return \Venne\Forms\Form
	 */
	protected function createComponentForm()
	{
		$form = $this->setupFormFactory->invoke($this->getExtendedElement());
		$form->onSuccess[] = $this->processForm;
		return $form;
	}


	public function processForm()
	{
		$this->getPresenter()->redirect('this');
	}
}
