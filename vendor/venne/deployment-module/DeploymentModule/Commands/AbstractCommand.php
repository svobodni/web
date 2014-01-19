<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DeploymentModule\Commands;

use DeploymentModule\DeploymentManager;
use Symfony\Component\Console\Command\Command;

/**
 * Command to execute DQL queries in a given EntityManager.
 */
abstract class AbstractCommand extends Command
{

	/** @var DeploymentManager */
	private $deploymentManager;


	/**
	 * @param DeploymentManager $deploymentManager
	 */
	public function __construct(DeploymentManager $deploymentManager)
	{
		parent::__construct();

		$this->deploymentManager = $deploymentManager;
	}


	/**
	 * @return DeploymentManager
	 */
	protected function  getDeploymentManager()
	{
		return $this->deploymentManager;
	}

}
