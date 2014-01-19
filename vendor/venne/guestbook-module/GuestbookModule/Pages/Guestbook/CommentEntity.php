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

use CmsModule\Content\Entities\ExtendedRouteEntity;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\GuestbookModule\Pages\Guestbook\CommentRepository")
 * @ORM\Table(name="guestbook_comment")
 */
class CommentEntity extends ExtendedRouteEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $author = '';

	/**
	 * @var CommentEntity
	 * @ORM\ManyToOne(targetEntity="CommentEntity", inversedBy="children")
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parent;

	/**
	 * @var CommentEntity[]
	 * @ORM\OneToMany(targetEntity="CommentEntity", mappedBy="parent")
	 */
	protected $children;


	protected function startup()
	{
		parent::startup();

		$this->setName(Strings::random(20));
		$this->route->setPublished(TRUE);
	}


	/**
	 * @param string $author
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
	}


	/**
	 * @return string
	 */
	public function getAuthor()
	{
		return $this->author;
	}


	/**
	 * @param CommentEntity $parent
	 */
	public function setParent($parent)
	{
		$this->parent = $parent;
	}


	/**
	 * @return CommentEntity
	 */
	public function getParent()
	{
		return $this->parent;
	}


	/**
	 * @param CommentEntity[] $children
	 */
	public function setChildren($children)
	{
		$this->children = $children;
	}


	/**
	 * @return CommentEntity[]
	 */
	public function getChildren()
	{
		return $this->children;
	}


	public function setText($text)
	{
		$name = Strings::substring($text, 0, 30) . '...';
		$this->route
			->setName($name)
			->setTitle($name)
			->setText($text);
	}


	public function getText()
	{
		return $this->route->getText();
	}
}
