<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GuestbookModule\Pages\Guestbook;

use CmsModule\Content\Entities\ExtendedPageEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CmsModule\Content\Repositories\PageRepository")
 * @ORM\Table(name="guestbook_page")
 */
class PageEntity extends ExtendedPageEntity
{

	const PRIVILEGE_EDIT_OWN = 'edit_own';

	const PRIVILEGE_EDIT = 'edit';

	const PRIVILEGE_DELETE_OWN = 'delete_own';

	const PRIVILEGE_DELETE = 'delete';

	/**
	 * @var ArrayCollection|CommentEntity[]
	 * @ORM\OneToMany(targetEntity="CommentEntity", mappedBy="page")
	 */
	protected $comments;

	/**
	 * @var string
	 * @ORM\Column(type="integer")
	 */
	protected $itemsPerPage = 10;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $messageMaxLength;


	/**
	 * @param $comments
	 */
	public function setComments($comments)
	{
		$this->comments = $comments;
	}


	/**
	 * @return ArrayCollection|CommentEntity[]
	 */
	public function getComments()
	{
		return $this->comments;
	}


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
	 * @param int $messageMaxLength
	 */
	public function setMessageMaxLength($messageMaxLength)
	{
		$this->messageMaxLength = $messageMaxLength ? : NULL;
	}


	/**
	 * @return int
	 */
	public function getMessageMaxLength()
	{
		return $this->messageMaxLength;
	}


	public function getPrivileges()
	{
		return parent::getPrivileges() + array(
			self::PRIVILEGE_EDIT_OWN => 'edit own comments',
			self::PRIVILEGE_DELETE_OWN => 'delete own comments',
			self::PRIVILEGE_EDIT => 'edit comments from all authors',
			self::PRIVILEGE_DELETE => 'delete comments from all authors',
		);
	}
}
