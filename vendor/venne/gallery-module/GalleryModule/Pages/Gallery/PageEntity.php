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

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\GalleryModule\Pages\Gallery\PageRepository")
 * @ORM\Table(name="gallery_page")
 */
class PageEntity extends AbstractPageEntity
{

	/**
	 * @var \CmsModule\Content\Entities\PageEntity
	 * @ORM\ManyToOne(targetEntity="\CmsModule\Content\Entities\PageEntity")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $linkedPage;


	/**
	 * @param \CmsModule\Content\Entities\PageEntity $linkedPage
	 */
	public function setLinkedPage($linkedPage)
	{
		$this->linkedPage = $linkedPage;
	}


	/**
	 * @return \CmsModule\Content\Entities\PageEntity
	 */
	public function getLinkedPage()
	{
		return $this->linkedPage;
	}

}
