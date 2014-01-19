<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace FormsModule\DI;

use Nette\Config\CompilerExtension;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormsExtension extends CompilerExtension
{

	/**
	 * Processes configuration data. Intended to be overridden by descendant.
	 * @return void
	 */
	public function loadConfiguration()
	{
		parent::loadConfiguration();
		$container = $this->getContainerBuilder();

		$container->addDefinition($this->prefix('configMapper'))
			->setFactory('FormsModule\Mappers\ConfigMapper', array($container->parameters['configDir'] . '/config.neon'));

		$this->compiler->addExtension('twBootstrapRenderer', new \Kdyby\BootstrapFormRenderer\DI\RendererExtension);
	}
}
