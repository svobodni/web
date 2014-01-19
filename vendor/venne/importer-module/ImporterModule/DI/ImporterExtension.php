<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace ImporterModule\DI;

use Nette\Config\CompilerExtension;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ImporterExtension extends CompilerExtension
{

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
		$container->addDefinition($this->prefix('importerManager'))
			->setClass('ImporterModule\ImporterManager');
	}


	public function beforeCompile()
	{
		$container = $this->getContainerBuilder();
		$config = $container->getDefinition($this->prefix('importerManager'));

		foreach ($container->findByTag('importer') as $item => $tags) {
			$config->addSetup('addImporter', array($tags['name'], "@{$item}"));
		}
	}
}
