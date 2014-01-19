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

use CmsModule\Content\Entities\ExtendedPageEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractPageEntity extends ExtendedPageEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="integer")
	 */
	protected $itemsPerPage = 10;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean")
	 */
	protected $autoNotation = TRUE;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $notationLength = 200;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean")
	 */
	protected $notationInHtml = FALSE;


	/**
	 * @param string $itemsPerPage
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = $itemsPerPage;
	}


	/**
	 * @return string
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}


	/**
	 * @param boolean $autoNotation
	 */
	public function setAutoNotation($autoNotation)
	{
		$this->autoNotation = $autoNotation;
	}


	/**
	 * @return boolean
	 */
	public function getAutoNotation()
	{
		return $this->autoNotation;
	}


	/**
	 * @param int $notationLength
	 */
	public function setNotationLength($notationLength)
	{
		$this->notationLength = $notationLength;
	}


	/**
	 * @return int
	 */
	public function getNotationLength()
	{
		return $this->notationLength;
	}


	/**
	 * @param boolean $notationInHtml
	 */
	public function setNotationInHtml($notationInHtml)
	{
		$this->notationInHtml = $notationInHtml;
	}


	/**
	 * @return boolean
	 */
	public function getNotationInHtml()
	{
		return $this->notationInHtml;
	}
}
