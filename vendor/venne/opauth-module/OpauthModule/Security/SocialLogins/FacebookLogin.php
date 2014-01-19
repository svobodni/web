<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace OpauthModule\Security\SocialLogins;

use CmsModule\Security\Entities\LoginProviderEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FacebookLogin extends AbstractLogin
{


	/** @var string */
	protected $appId;

	/** @var string */
	protected $secret;

	/** @var string */
	protected $scope;


	/**
	 * @param $appId
	 * @param $secret
	 * @param $scope
	 */
	public function injectParameters($appId, $secret, $scope)
	{
		$this->appId = $appId;
		$this->secret = $secret;
		$this->scope = $scope;
	}


	public static function getType()
	{
		return 'Facebook';
	}


	/**
	 * @return array
	 */
	protected function getConfig()
	{
		return array(
			'app_id' => $this->appId,
			'app_secret' => $this->secret,
			//'scope' => $this->scope,
		);
	}


	protected function fillLoginEntity(LoginProviderEntity $entity, $raw)
	{
		$entity->setNick($raw['username']);
		$entity->setEmail($raw['username'] . '@facebook.com');
		$entity->setName($raw['first_name']);
		$entity->setSurname($raw['last_name']);
		$entity->setProfileUrl($raw['link']);
		$entity->setGender($raw['gender']);
	}

}
