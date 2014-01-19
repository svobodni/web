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

use BlogModule\Pages\Blog\RouteRepository;
use CmsModule\Content\Elements\BaseElement;
use Venne\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class BlogElement extends BaseElement
{

	/** @var RouteRepository */
	private $routeRepository;

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
	 * @param RouteRepository $routeRepository
	 */
	public function injectRouteRepository(RouteRepository $routeRepository)
	{
		$this->routeRepository = $routeRepository;
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
		$dql = $this->routeRepository->createQueryBuilder('a');

		if (count($this->getExtendedElement()->pages) > 0) {
			$ids = array();
			foreach ($this->getExtendedElement()->pages as $page) {
				$ids[] = $page->id;
			}

			$dql = $dql->join('a.extendedPage', 'p');
			$dql = $dql->andWhere('p.id IN (:ids)')->setParameter('ids', $ids);
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
