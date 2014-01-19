<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace AssetsModule\DI;

use Nette\Config\CompilerExtension;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AssetsExtension extends CompilerExtension
{

	/**
	 * Processes configuration data. Intended to be overridden by descendant.
	 * @return void
	 */
	public function loadConfiguration()
	{
		parent::loadConfiguration();
		$container = $this->getContainerBuilder();

		// macros
		$container->getDefinition('nette.latte')
			->addSetup('AssetsModule\Macros\CssMacro::install(?->compiler, ?, ?)', array('@self', '@venne.moduleHelpers', '%wwwDir%'))
			->addSetup('AssetsModule\Macros\JsMacro::install(?->compiler, ?, ?)', array('@self', '@venne.moduleHelpers', '%wwwDir%'));


		// collections
		$container->addDefinition($this->prefix('cssFileCollection'))
			->setClass('AssetsModule\CssFileCollection');

		$container->addDefinition($this->prefix('jsFileCollection'))
			->setClass('AssetsModule\JsFileCollection');


		// compilers
		$cssCompiler = $container->addDefinition($this->prefix('cssCompiler'))
			->setClass('WebLoader\Compiler')
			->setFactory('WebLoader\Compiler::createCssCompiler', array($this->prefix('@cssFileCollection'), '%wwwDir%/cache'))
			->addSetup('$service->addFileFilter(?)', array($this->prefix('@cssUrlsFilter')))
			->addSetup('setCheckLastModified', array('%debugMode%'))
			->addSetup('setJoinFiles', array(!$container->parameters['debugMode']))
			->setAutowired(FALSE);

		$jsCompiler = $container->addDefinition($this->prefix('jsCompiler'))
			->setClass('WebLoader\Compiler')
			->setFactory('WebLoader\Compiler::createJsCompiler', array($this->prefix('@jsFileCollection'), '%wwwDir%/cache'))
			->addSetup('setCheckLastModified', array('%debugMode%'))
			->addSetup('setJoinFiles', array(!$container->parameters['debugMode']))
			->setAutowired(FALSE);


		// loaders
		$container->addDefinition($this->prefix('cssLoader'))
			->setClass('AssetsModule\CssLoader', array($this->prefix('@cssCompiler'), '/cache'))
			->setShared(FALSE)
			->setAutowired(FALSE)
			->addTag('widget', 'css');

		$container->addDefinition($this->prefix('jsLoader'))
			->setClass('AssetsModule\JavaScriptLoader', array($this->prefix('@jsCompiler'), '/cache'))
			->setShared(FALSE)
			->setAutowired(FALSE)
			->addTag('widget', 'js');


		// filters
		$container->addDefinition($this->prefix('cssUrlsFilter'))
			->setClass('WebLoader\Filter\CssUrlsFilter', array('%wwwDir%'))
			->addSetup('$service = new WebLoader\Filter\CssUrlsFilter(?, $this->parameters[\'basePath\'])', array('%wwwDir%'));

		$container->addDefinition($this->prefix('cssMinFilter'))
			->setClass('AssetsModule\Filters\CssMinFilter');

		$container->addDefinition($this->prefix('jsMinFilter'))
			->setClass('AssetsModule\Filters\JsMinFilter');

		if (!$container->parameters['debugMode']) {
			$cssCompiler->addSetup('addFilter', $this->prefix('@cssMinFilter'));
			$jsCompiler->addSetup('addFilter', $this->prefix('@jsMinFilter'));
		}
	}
}
