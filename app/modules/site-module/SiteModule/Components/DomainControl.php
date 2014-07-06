<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Components;

use CmsModule\Content\Control;
use CmsModule\Content\Repositories\DomainRepository;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class DomainControl extends Control
{

	/** @var DomainRepository */
	private $domainRepository;


	/**
	 * @param DomainRepository $domainRepository
	 */
	public function __construct(DomainRepository $domainRepository)
	{
		parent::__construct();

		$this->domainRepository = $domainRepository;
	}


	public function getDomains()
	{
		return $this->domainRepository->findBy(array(), array('name' => 'ASC'));
	}


	public function getCurrentDomain()
	{
		return $this->domainRepository->findOneBy(array('domain' => $this->presenter->_domain));
	}

}
