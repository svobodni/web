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

use CmsModule\Content\Entities\ExtendedRouteEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractArticleEntity extends ExtendedRouteEntity
{

	/**
	 * @var AbstractCategoryEntity
	 * @ORM\ManyToOne(targetEntity="::dynamic", cascade={"persist"})
	 */
	protected $category;

	/**
	 * @var AbstractCategoryEntity[]
	 * @ORM\ManyToMany(targetEntity="::dynamic", cascade={"persist"})
	 * @ORM\JoinTable(
	 *      joinColumns={@ORM\JoinColumn(name="articleentity_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="categoryentity_id", referencedColumnName="id", onDelete="CASCADE")}
	 *      )
	 */
	protected $categories;


	public static function getCategoryName()
	{
		return static::getReflection()->getNamespaceName() . '\CategoryEntity';
	}


	public static function getCategoriesName()
	{
		return static::getCategoryName();
	}


	protected function startup()
	{
		parent::startup();

		$this->categories = new ArrayCollection;
	}


	/**
	 * @param \BlogModule\Pages\Blog\AbstractCategoryEntity $category
	 */
	public function setCategory(AbstractCategoryEntity $category = NULL)
	{
		$this->category = $category;
		$this->route->parent = $category ? $category->route : $this->page->mainRoute;
	}


	/**
	 * @return \BlogModule\Pages\Blog\AbstractCategoryEntity
	 */
	public function getCategory()
	{
		return $this->category;
	}


	/**
	 * @param \BlogModule\Pages\Blog\AbstractCategoryEntity[] $categories
	 */
	public function setCategories($categories)
	{
		$this->categories = $categories;
	}


	/**
	 * @return \BlogModule\Pages\Blog\AbstractCategoryEntity[]
	 */
	public function getCategories()
	{
		return $this->categories;
	}

}
