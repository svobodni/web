<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DoctrineModule\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @ORM\MappedSuperclass()
 */
abstract class IdentifiedEntity extends BaseEntity implements IEntity
{


	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;


	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @param integer $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}
}

