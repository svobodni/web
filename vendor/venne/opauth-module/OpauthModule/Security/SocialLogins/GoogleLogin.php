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

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class GoogleLogin extends AbstractLogin
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
		return 'Google';
	}


	/**
	 * @return array
	 */
	protected function getConfig()
	{
		return array(
			'client_id' => $this->appId,
			'client_secret' => $this->secret,
			//'scope' => $this->scope,
		);
	}

}
