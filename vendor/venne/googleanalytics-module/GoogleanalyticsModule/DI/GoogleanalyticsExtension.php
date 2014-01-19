<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule\DI;

use Venne;
use Venne\Config\CompilerExtension;
use Nette\Application\Routers\Route;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class GoogleanalyticsExtension extends CompilerExtension
{

	const DIR = 'googleanalytics';

	/** @var array */
	public $defaults = array(
		'account' => array(
			'activated' => FALSE,
			'accountId' => '',
		),
		'api' => array(
			'activated' => FALSE,
			'applicationName' => NULL,
			'clientId' => NULL,
			'clientMail' => NULL,
			'gaId' => NULL,
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

		$container->addDefinition($this->prefix('analyticsManager'))
			->setClass('GoogleanalyticsModule\AnalyticsManager')
			->setArguments(array(
				$config['account']['activated'],
				$config['account']['accountId'],
				$config['api']['activated'],
				$config['api']['applicationName'],
				$config['api']['clientId'],
				$config['api']['clientMail'],
				$config['api']['gaId'],
				$container->parameters['dataDir'] . '/' . self::DIR . '/key.p12',
				$container->parameters['modules']['googleanalytics']['path'],
			));

		if (!file_exists($container->parameters['dataDir'] . '/' . self::DIR)) {
			umask(0000);
			mkdir($container->parameters['dataDir'] . '/' . self::DIR);
		}
	}
}
