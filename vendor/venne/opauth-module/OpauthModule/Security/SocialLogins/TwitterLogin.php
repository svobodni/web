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
class TwitterLogin extends AbstractLogin
{


	/** @var string */
	protected $key;

	/** @var string */
	protected $secret;


	/**
	 * @param $key
	 * @param $secret
	 */
	public function injectParameters($key, $secret)
	{
		$this->key = $key;
		$this->secret = $secret;
	}


	public static function getType()
	{
		return 'Twitter';
	}


	/**
	 * @return array
	 */
	protected function getConfig()
	{
		return array(
			'key' => $this->key,
			'secret' => $this->secret,
		);
	}


	protected function fillLoginEntity(LoginProviderEntity $entity, $raw)
	{
		$entity->setNick($raw['pepakriz']);
		$entity->setName($raw['first_name']);
		$entity->setSurname($raw['last_name']);
		$entity->setProfileUrl($raw['link']);
		$entity->setGender($raw['gender']);
	}

}
