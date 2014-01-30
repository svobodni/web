<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace EventsModule\Pages\Events;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\EventsModule\Pages\Events\CategoryRepository")
 * @ORM\Table(name="events_category")
 */
class CategoryEntity extends \BlogModule\Pages\Blog\AbstractCategoryEntity
{

}
