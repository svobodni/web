<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CmsModule\Pages\Text\NavigationElement;

use CmsModule\Content\Elements\ExtendedElementEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\DoctrineModule\Repositories\BaseRepository")
 * @ORM\Table(name="navigation_element")
 */
class NavigationEntity extends ExtendedElementEntity
{

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $startDepth = 0;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $maxDepth = 2;

	/**
	 * @var \CmsModule\Content\Entities\PageEntity
	 * @ORM\ManyToOne(targetEntity="\CmsModule\Content\Entities\PageEntity")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $root;


	/**
	 * @param int $maxDepth
	 */
	public function setMaxDepth($maxDepth)
	{
		$this->maxDepth = $maxDepth;
	}


	/**
	 * @return int
	 */
	public function getMaxDepth()
	{
		return $this->maxDepth;
	}


	/**
	 * @param int $startDepth
	 */
	public function setStartDepth($startDepth)
	{
		$this->startDepth = $startDepth;
	}


	/**
	 * @return int
	 */
	public function getStartDepth()
	{
		return $this->startDepth;
	}


	/**
	 * @param mixed $root
	 */
	public function setRoot($root)
	{
		$this->root = $root;
	}


	/**
	 * @return mixed
	 */
	public function getRoot()
	{
		return $this->root;
	}

}
