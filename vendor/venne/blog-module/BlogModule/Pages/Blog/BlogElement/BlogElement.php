<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace BlogModule\Pages\Blog\BlogElement;

use BlogModule\Pages\Blog\ArticleRepository;
use CmsModule\Content\Elements\BaseElement;
use Venne\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class BlogElement extends BaseElement
{

	/** @var ArticleRepository */
	private $articleRepository;

	/** @var TextFormFactory */
	protected $setupFormFactory;


	/**
	 * @param TextFormFactory $setupForm
	 */
	public function injectSetupForm(FormFactory $setupForm)
	{
		$this->setupFormFactory = $setupForm;
	}


	/**
	 * @param ArticleRepository $articleRepository
	 */
	public function injectArticleRepository(ArticleRepository $articleRepository)
	{
		$this->articleRepository = $articleRepository;
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


	public function getItems()
	{
		$query = $this->getQueryBuilder()
			->join('a.route', 'r')
			->andWhere('r.published = :true')->setParameter('true', TRUE)
			->andWhere('r.released <= :now')
			->andWhere('r.expired IS NULL OR r.expired > :now')->setParameter('now', new \DateTime)
			->setMaxResults($this->getExtendedElement()->itemsPerPage)
			->setFirstResult($this['vp']->getPaginator()->getOffset())
			->orderBy('r.released', 'DESC')
			->getQuery();

		return $query->getResult();
	}


	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getQueryBuilder()
	{
		$dql = $this->articleRepository->createQueryBuilder('a');

		if (count($this->getExtendedElement()->pages) > 0) {
			$ids = array();
			foreach ($this->getExtendedElement()->pages as $page) {
				$ids[] = $page->id;
			}

			$dql
				->leftJoin('a.extendedPage', 'p')
				->andWhere('p.id IN (:ids)')->setParameter('ids', $ids);
		}

		if (count($this->getExtendedElement()->categories) > 0) {
			$ids = array();
			foreach ($this->getExtendedElement()->categories as $category) {
				$ids[] = $category->id;
			}

			$dql
				->leftJoin('a.categories', 'c')
				->andWhere('(a.category IN (:cids) OR c.id IN (:cids))')->setParameter('cids', $ids);
		}

		return $dql;
	}


	protected function createComponentVp()
	{
		$vp = new \CmsModule\Components\PaginationControl;
		$pg = $vp->getPaginator();
		$pg->setItemsPerPage($this->getExtendedElement()->itemsPerPage);
		$pg->setItemCount($this->getQueryBuilder()->select("COUNT(a.id)")->getQuery()->getSingleScalarResult());
		return $vp;
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
