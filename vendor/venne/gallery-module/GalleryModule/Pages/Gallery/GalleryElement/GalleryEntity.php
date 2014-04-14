<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GalleryModule\Pages\Gallery\GalleryElement;

use CmsModule\Content\Elements\ExtendedElementEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\DoctrineModule\Repositories\BaseRepository")
 * @ORM\Table(name="gallery_element")
 */
class GalleryEntity extends ExtendedElementEntity
{

	/**
	 * @var \GalleryModule\Pages\Gallery\PageEntity
	 * @ORM\OneToOne(targetEntity="\GalleryModule\Pages\Gallery\PageEntity")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $page;


	/**
	 * @param \GalleryModule\Pages\Gallery\PageEntity[] $page
	 */
	public function setPage($page)
	{
		$this->page = $page;
	}


	/**
	 * @return \GalleryModule\Pages\Gallery\PageEntity[]
	 */
	public function getPage()
	{
		return $this->page;
	}

}
