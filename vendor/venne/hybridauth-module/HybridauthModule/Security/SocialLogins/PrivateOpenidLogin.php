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


/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PrivateOpenidLogin extends AbstractLogin
{


	public function injectParameters($server)
	{
		$this->setAuthenticationParameters(array('openid_identifier' => $server));
	}


	public static function getType()
	{
		return 'Private OpenID';
	}


	protected function getHybridType()
	{
		return 'OpenID';
	}

}
