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

use BlogModule\Pages\Blog\AbstractArticleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractCategoryEntity extends AbstractArticleEntity
{

	/**
	 * @var ItemEntity[]
	 * @ORM\OneToMany(targetEntity="ItemEntity", mappedBy="category", cascade={"persist"}, orphanRemoval=true)
	 * @ORM\OrderBy({"position" = "ASC"})
	 */
	protected $items;

	/**
	 * @var \CmsModule\Content\Entities\RouteEntity
	 * @ORM\ManyToOne(targetEntity="\CmsModule\Content\Entities\RouteEntity")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $linkedRoute;


	protected function startup()
	{
		parent::startup();

		$this->items = new ArrayCollection;
	}


	/**
	 * @param ItemEntity[] $items
	 */
	public function setItems($items)
	{
		$this->items = $items;
	}


	/**
	 * @return ItemEntity[]
	 */
	public function getItems()
	{
		return $this->items;
	}


	/**
	 * @param \CmsModule\Content\Entities\RouteEntity $linkedRoute
	 */
	public function setLinkedRoute($linkedRoute)
	{
		$this->linkedRoute = $linkedRoute;
	}


	/**
	 * @return \CmsModule\Content\Entities\RouteEntity
	 */
	public function getLinkedRoute()
	{
		return $this->linkedRoute;
	}

}
