<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace HybridauthModule\Security\SocialLogins;

use CmsModule\Security\Entities\LoginProviderEntity;
use CmsModule\Security\LoginProviders\BaseLoginProvider;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractLogin extends BaseLoginProvider
{

	/** @var \Hybrid_User_Profile */
	protected $user;

	/** @var boolean */
	protected $_load;

	/** @var array */
	protected $parameters;

	/** @var \Hybrid_Auth */
	private $hybridauth;


	protected function load()
	{
		if ($this->_load) {
			return;
		}
		$this->_load = true;

		$this->user = $this->getHybridauth()
			->authenticate($this->getHybridType(), $this->parameters)
			->getUserProfile();
	}


	protected function getHybridType()
	{
		return $this->getType();
	}


	/**
	 * @return LoginProviderEntity
	 */
	protected function createLoginProviderEntity()
	{
		$type = $this->getHybridType();

		//dump($this->getCallbackUrl());die($this->getCallbackUrl());

		$params = array(
			'base_url' => $this->getCallbackUrl(),
			'providers' => array(
				$type => ($this->getConfig() + array('enabled' => TRUE))
			),
		);

		$hybridauth = new \Hybrid_Auth($params);

		if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done'])) {
			\Hybrid_Endpoint::process();
		}

		/** @var \Hybrid_User_Profile $user */
		$user = $hybridauth
			->authenticate($this->getHybridType(), $this->authenticationParameters)
			->getUserProfile();

		$ret = new LoginProviderEntity($user->identifier, static::getType());
		return $ret;
	}


	protected function getCallbackUrl()
	{
		if (isset($_GET['_hybrid_auth_url'])) {
			return urldecode($_GET['_hybrid_auth_url']) . '&_hybrid_auth_url=' . $_GET['_hybrid_auth_url'];
		}

		$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$url = explode('?', $url, 2);
		$url = rtrim($url[0], '/') . '?' . $url[1];
		return $url . '&_hybrid_auth_url=' . urlencode($url);
	}


	/**
	 * @return array
	 */
	protected function getConfig()
	{
		return array();
	}


	public function getData()
	{
		$this->load();

		return (array)$this->user;
	}


	/**
	 * @return SocialLoginEntity
	 */
	protected function getSocialLoginEntity()
	{
		$this->load();

		$entity = new SocialLoginEntity;
		$entity->setData($this->getData());
		$entity->setUniqueKey($this->getKey());
		$entity->setType($this->getType());

		return $entity;
	}

}
