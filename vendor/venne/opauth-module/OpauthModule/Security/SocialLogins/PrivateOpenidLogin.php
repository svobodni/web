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
class PrivateOpenidLogin extends AbstractLogin
{


	public function injectParameters($server)
	{
		$this->setAuthenticationParameters(array('server' => $server));
	}


	public static function getType()
	{
		return 'Private OpenID';
	}


	protected function getOpauthType()
	{
		return 'OpenID';
	}


	/**
	 * @return array
	 */
	protected function getConfig()
	{
		$_POST['openid_url'] = $this->authenticationParameters['server'];
		return array();
	}

	protected function fillLoginEntity(LoginProviderEntity $entity, $raw)
	{
		$name = explode(' ', $raw['namePerson'], 2);

		$entity->setEmail($raw['contact/email']);
		$entity->setName($name[0]);
		$entity->setSurname($name[1]);
	}

}
