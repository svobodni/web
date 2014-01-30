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

use CmsModule\Content\Elements\ExtendedElementEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class BaseElementEntity extends ExtendedElementEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="integer")
	 */
	protected $itemsPerPage = 5;

	/**
	 * @var \BlogModule\Pages\Blog\PageEntity[]
	 * @ORM\ManyToMany(targetEntity="\BlogModule\Pages\Blog\PageEntity")
	 * @ORM\JoinTable(
	 *       joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
	 *       inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
	 *       )
	 */
	protected $pages;

	/**
	 * @var \BlogModule\Pages\Blog\AbstractCategoryEntity[]
	 * @ORM\ManyToMany(targetEntity="\BlogModule\Pages\Blog\CategoryEntity")
	 * @ORM\JoinTable(
	 *       joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
	 *       inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
	 *       )
	 */
	protected $categories;


	protected function startup()
	{
		parent::startup();

		$this->pages = new ArrayCollection;
		$this->categories = new ArrayCollection;
	}


	/**
	 * @param string $itemsPerPage
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = $itemsPerPage;
	}


	/**
	 * @return string
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}


	/**
	 * @param \CmsModule\Content\Entities\PageEntity[] $pages
	 */
	public function setPages($pages)
	{
		$this->pages = $pages;
	}


	/**
	 * @return \CmsModule\Content\Entities\PageEntity[]
	 */
	public function getPages()
	{
		return $this->pages;
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
