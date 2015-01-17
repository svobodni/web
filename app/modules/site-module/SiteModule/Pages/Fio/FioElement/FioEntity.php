<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Fio\FioElement;

use CmsModule\Content\Elements\ExtendedElementEntity;
use Doctrine\ORM\Mapping as ORM;
use SiteModule\Pages\Fio\PageEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\DoctrineModule\Repositories\BaseRepository")
 * @ORM\Table(name="fio_element")
 */
class FioEntity extends ExtendedElementEntity
{

	/**
	 * @var \SiteModule\Pages\Fio\PageEntity|null
	 *
	 * @ORM\ManyToOne(targetEntity="\SiteModule\Pages\Fio\PageEntity")
	 */
	protected $page;

	/**
	 * @var int
	 *
	 * @ORM\Column(type="integer")
	 */
	protected $itemsPerPage = 6;

	/**
	 * @return mixed
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @param mixed $page
	 */
	public function setPage(PageEntity $page)
	{
		$this->page = $page;
	}

	/**
	 * @return int
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}

	/**
	 * @param int $itemsPerPage
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = (int)$itemsPerPage;
	}


}
