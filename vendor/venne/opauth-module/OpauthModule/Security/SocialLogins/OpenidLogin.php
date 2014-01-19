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
use Nette\Forms\Container;


/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class OpenidLogin extends AbstractLogin
{

	public static function getType()
	{
		return 'OpenID';
	}


	public function getFormContainer()
	{
		$container = new Container;
		$container->addText('server', 'OpenID');
		return $container;
	}


	/**
	 * @return array
	 */
	protected function getConfig()
	{
		$_GET['openid_return_to'] = 'http://localhost' . rtrim($this->getCallbackUrl(), '&_opauth_action=1');;
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
