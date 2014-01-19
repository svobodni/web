<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TranslatorModule\DI;

use Venne;
use Nette\Config\CompilerExtension;
use Nette\Application\Routers\Route;
use Nette\Config\Configurator;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class TranslatorExtension extends CompilerExtension
{

	public $defaults = array(
		'dictionaries' => array(),
	);


	/**
	 * Processes configuration data. Intended to be overridden by descendant.
	 * @return void
	 */
	public function loadConfiguration()
	{
		parent::loadConfiguration();
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$container->addDefinition($this->prefix('extractor'))
			->setClass('TranslatorModule\Extraction\Extractor')
			->addSetup('$service->addFilter(?)', array(new \Nette\DI\Statement('TranslatorModule\Extraction\Filters\LatteFilter')))
			->addSetup('$service->addFilter(?)', array(new \Nette\DI\Statement('TranslatorModule\Extraction\Filters\PhpFilter')));

		$translator = $container->addDefinition($this->prefix('translator'))
			->setClass('TranslatorModule\Translator', array('%productionMode%'))
			->addSetup('setCache', array('@cacheStorage'));


		foreach ($config['dictionaries'] as $dictionary) {
			$translator->addSetup('$service->addDictionary(?)', array(new \Nette\DI\Statement('TranslatorModule\Dictionary', array($dictionary))));
		}

		// Commands
		$container->addDefinition($this->prefix('extractCommand'))
			->setClass('TranslatorModule\Commands\ExtractCommand')
			->addTag('command');
	}


	/**
	 * Register extension to compiler.
	 *
	 * @param \Nette\Config\Configurator
	 * @param string
	 */
	public static function register(Configurator $configurator, $name = 'translator')
	{
		$class = get_called_class();
		$configurator->onCompile[] = function(Configurator $configurator, \Nette\Config\Compiler $compiler) use($class, $name)
		{
			$compiler->addExtension($name, new $class);
		};
	}
}
