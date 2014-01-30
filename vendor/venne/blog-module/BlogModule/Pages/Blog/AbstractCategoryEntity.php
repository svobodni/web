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
class AbstractCategoryEntity extends ExtendedRouteEntity
{

	/**
	 * @var AbstractCategoryEntity
	 * @ORM\ManyToOne(targetEntity="\BlogModule\Pages\Blog\CategoryEntity", inversedBy="children")
	 */
	protected $parent;


	/**
	 * @var AbstractCategoryEntity[]
	 * @ORM\OneToMany(targetEntity="\BlogModule\Pages\Blog\CategoryEntity", mappedBy="parent")
	 */
	protected $children;


	protected function startup()
	{
		parent::startup();

		$this->children = new ArrayCollection;
	}


	/**
	 * @param \BlogModule\Pages\Blog\AbstractCategoryEntity[] $children
	 */
	public function setChildren($children)
	{
		$this->children = $children;
	}


	/**
	 * @return \BlogModule\Pages\Blog\AbstractCategoryEntity[]
	 */
	public function getChildren()
	{
		return $this->children;
	}


	/**
	 * @param \BlogModule\Pages\Blog\AbstractCategoryEntity $parent
	 */
	public function setParent(AbstractCategoryEntity $parent = NULL)
	{
		if (!$parent && $this->parent) {
			$p = $this->parent->route;
			while($p->class === $this->route->class) {
				$p = $p->parent;
			}

			$this->route->setParent($p);
		}

		$this->parent = $parent;

		if ($parent) {
			$this->route->setParent($parent->route);
		}
	}


	/**
	 * @return \BlogModule\Pages\Blog\AbstractCategoryEntity
	 */
	public function getParent()
	{
		return $this->parent;
	}


	/**
	 * @return string
	 */
	protected function getPresenterName()
	{
		$presenter = explode(':', parent::getPresenterName());
		$presenter[count($presenter) - 2] = 'Route';

		return join(':', $presenter);
	}

}
