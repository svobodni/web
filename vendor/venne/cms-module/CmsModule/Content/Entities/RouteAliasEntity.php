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
 * @ORM\Entity(repositoryClass="\CmsModule\Content\Repositories\RouteAliasRepository")
 * @ORM\Table(name="route_alias")
 */
class RouteAliasEntity extends \DoctrineModule\Entities\IdentifiedEntity
{

	/**
	 * @var RouteEntity
	 *
	 * @ORM\ManyToOne(targetEntity="\CmsModule\Content\Entities\RouteEntity", inversedBy="aliases")
	 * @ORM\JoinColumn(name="route_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $route;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", name="url")
	 */
	protected $aliasUrl;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", name="lang", nullable=true)
	 */
	protected $aliasLang;

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", name="domain", nullable=true)
	 */
	protected $aliasDomain;

	/**
	 * @return RouteEntity
	 */
	public function getRoute()
	{
		return $this->route;
	}

	/**
	 * @param RouteEntity $route
	 */
	public function setRoute(RouteEntity $route)
	{
		$this->route = $route;
	}

	/**
	 * @return string
	 */
	public function getAliasUrl()
	{
		return $this->aliasUrl;
	}

	/**
	 * @param string $aliasUrl
	 */
	public function setAliasUrl($aliasUrl)
	{
		$this->aliasUrl = $aliasUrl;
	}

	/**
	 * @return null|string
	 */
	public function getAliasLang()
	{
		return $this->aliasLang;
	}

	/**
	 * @param null|string $aliasLang
	 */
	public function setAliasLang($aliasLang)
	{
		$this->aliasLang = $aliasLang ? $aliasLang : null;
	}

	/**
	 * @return null|string
	 */
	public function getAliasDomain()
	{
		return $this->aliasDomain;
	}

	/**
	 * @param null|string $aliasDomain
	 */
	public function setAliasDomain($aliasDomain)
	{
		$this->aliasDomain = $aliasDomain ? $aliasDomain : null;
	}

}
