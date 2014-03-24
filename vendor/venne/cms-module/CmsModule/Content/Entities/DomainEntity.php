<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CmsModule\Content\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CmsModule\Content\Repositories\DomainRepository")
 * @ORM\Table(name="domain")
 * @ORM\HasLifecycleCallbacks
 */
class DomainEntity extends \DoctrineModule\Entities\NamedEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $domain;

	/**
	 * @var PageEntity
	 * @ORM\OneToOne(targetEntity="\CmsModule\Content\Entities\PageEntity")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $page;


	/**
	 * @param string $domain
	 */
	public function setDomain($domain)
	{
		$this->domain = $domain;
	}


	/**
	 * @return string
	 */
	public function getDomain()
	{
		return $this->domain;
	}


	/**
	 * @param \CmsModule\Content\Entities\PageEntity $page
	 */
	public function setPage(PageEntity $page)
	{
		if ($this->page && $this->page !== $page) {
			$this->page->mainRoute->setDomain(NULL);
		}

		$this->page = $page;
		$this->page->mainRoute->setDomain($this);
	}


	/**
	 * @return \CmsModule\Content\Entities\PageEntity
	 */
	public function getPage()
	{
		return $this->page;
	}


	/**
	 * @ORM\PreRemove()
	 */
	public function preRemove()
	{
		$this->page->mainRoute->setDomain(NULL);
	}

}
