<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace EventsModule\Pages\Events\EventsElement;

use BlogModule\Pages\Blog\BaseElementEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\DoctrineModule\Repositories\BaseRepository")
 * @ORM\Table(name="events_element")
 */
class EventsEntity extends BaseElementEntity
{

	/**
	 * @var \EventsModule\Pages\Events\PageEntity[]
	 * @ORM\ManyToMany(targetEntity="\EventsModule\Pages\Events\PageEntity")
	 * @ORM\JoinTable(
	 *       joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
	 *       inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
	 *       )
	 */
	protected $pages;

}
