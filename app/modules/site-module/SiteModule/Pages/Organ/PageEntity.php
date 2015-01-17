<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Organ;

use CmsModule\Content\Entities\ExtendedPageEntity;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Json;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CmsModule\Content\Repositories\PageRepository")
 * @ORM\Table(name="svobodni_organ_page")
 */
class PageEntity extends ExtendedPageEntity
{

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 */
	protected $apiUrl = '';

	/**
	 * @var int|null
	 *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $section;

	/**
	 * @return string
	 */
	public function getApiUrl()
	{
		return $this->apiUrl;
	}

	/**
	 * @param string $apiUrl
	 */
	public function setApiUrl($apiUrl)
	{
		$this->apiUrl = $apiUrl;
	}

	/**
	 * @return int|null
	 */
	public function getSection()
	{
		return $this->section;
	}

	/**
	 * @param int $section
	 */
	public function setSection($section)
	{
		$this->section = $section;
	}

}
