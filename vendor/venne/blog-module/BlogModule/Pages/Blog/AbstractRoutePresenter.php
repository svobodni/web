<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace BlogModule\Pages\Blog;

use CmsModule\Content\Presenters\ItemsPresenter;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractRoutePresenter extends ItemsPresenter
{

	protected function getItemsPerPage()
	{
		return $this->extendedPage->itemsPerPage;
	}


	/**
	 * @return CategoryRepository
	 */
	protected function getCategoryRepository()
	{
		return NULL;
	}


	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getQueryBuilder()
	{
		$qb = parent::getQueryBuilder();

		if ($this->extendedRoute instanceof AbstractCategoryEntity) {
			$qb
				->leftJoin('a.categories', 'cat')
				->andWhere('cat.id IN (:categories) OR a.category IN (:categories)')
				->setParameter('categories', $this->getCurrentCategories());
		}

		return $qb;
	}


	/**
	 * @param AbstractCategoryEntity|NULL $category
	 * @return array
	 */
	private function getCurrentCategories(AbstractCategoryEntity $category = NULL)
	{
		$category = $category ?: $this->extendedRoute;
		$ids = array($category->id);

		foreach ($category->children as $category) {
			$ids = array_merge($this->getCurrentCategories($category), $ids);
		}

		return $ids;
	}


	/**
	 * @return AbstractCategoryEntity[]
	 */
	public function getCategories()
	{
		return $this->getCategoryRepository()->findBy(array(
			'parent' => $this->extendedRoute instanceof AbstractCategoryEntity ? $this->extendedRoute : NULL,
			'extendedPage' => $this->extendedPage->id,
		));
	}
}
