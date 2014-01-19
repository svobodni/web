<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace OpauthModule\DI;

use Nette\Config\CompilerExtension;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class OpauthExtension extends CompilerExtension
{

	/** @var array */
	public $defaults = array(
		'providers' => array(
//			'google' => array(
//				'enabled' => FALSE,
//				'appId' => NULL,
//				'secret' => NULL,
//				'scope'   => 'https://www.googleapis.com/auth/userinfo.email',
//			),
			'facebook' => array(
				'enabled' => FALSE,
				'appId' => NULL,
				'secret' => NULL,
				'scope'   => 'email',
			),
			'openid' => array(
				'enabled' => FALSE,
			),
			'privateOpenid' => array(
				'enabled' => FALSE,
				'server' => 'http://www.myopenid.com',
			),
			'github' => array(
				'enabled' => FALSE,
				'appId' => NULL,
				'secret' => NULL,
				'scope'   => 'email',
			),
//			'twitter' => array(
//				'enabled' => FALSE,
//				'key' => NULL,
//				'secret' => NULL,
//			),
		),
	);

	/**
	 * Processes configuration data. Intended to be overridden by descendant.
	 * @return void
	 */
	public function loadConfiguration()
	{
		$this->compiler->parseServices(
			$this->getContainerBuilder(),
			$this->loadFromFile(dirname(dirname(__DIR__)) . '/Resources/config/config.neon')
		);

		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		foreach($config['providers'] as $name => $values) {
			if (!$values['enabled']) {
				continue;
			}

			unset($values['enabled']);

			$class = 'OpauthModule\Security\SocialLogins\\' . ucfirst($name) . 'Login';

			$container->addDefinition($this->prefix($name . 'Login'))
				->setClass($class)
				->setAutowired(false)
				->addSetup('injectParameters', $values)
				->addTag('loginProvider');
		}
	}

}
