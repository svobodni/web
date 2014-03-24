<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace PaymentsModule\Pages\Payments;

use CmsModule\Content\Entities\ExtendedPageEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CmsModule\Content\Repositories\PageRepository")
 * @ORM\Table(name="payments_page")
 */
class PageEntity extends ExtendedPageEntity
{

	/**
	 * @var AccountEntity[]
	 * @ORM\ManyToMany(targetEntity="\PaymentsModule\Admin\Payments\AccountEntity")
	 */
	protected $accounts;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $itemsPerPage = 100;


	protected function startup()
	{
		parent::startup();

		$this->accounts = new ArrayCollection;
	}


	/**
	 * @param \PaymentsModule\Admin\Payments\AccountEntity[] $accounts
	 */
	public function setAccounts($accounts)
	{
		$this->accounts = $accounts;
	}


	/**
	 * @return \PaymentsModule\Admin\Payments\AccountEntity[]
	 */
	public function getAccounts()
	{
		return $this->accounts;
	}


	/**
	 * @param int $itemsPerPage
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = $itemsPerPage;
	}


	/**
	 * @return int
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}

}
