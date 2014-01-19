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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to execute DQL queries in a given EntityManager.
 */
class ImportCommand extends AbstractCommand
{

	/**
	 * @see Console\Command\Command
	 */
	protected function configure()
	{
		$this
			->setName('deployment:import')
			->setDescription('Import database.')
			->addArgument('name', InputArgument::OPTIONAL, 'Backup name', '');
	}


	/**
	 * @see Console\Command\Command
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->getDeploymentManager()->loadBackup($input->getArgument('name'));
	}

}
