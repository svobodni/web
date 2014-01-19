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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to execute DQL queries in a given EntityManager.
 */
class ListCommand extends AbstractCommand
{

	/**
	 * @see Console\Command\Command
	 */
	protected function configure()
	{
		$this
			->setName('deployment:list')
			->setDescription('List backups.');
	}


	/**
	 * @see Console\Command\Command
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		foreach ($this->getDeploymentManager()->getBackups() as $name => $backup) {
			$output->writeln(sprintf('<info>%25s</info> | driver: <comment>%-12s</comment> | date: <comment>%s</comment>', $name ?: 'untitled', $backup['driver'], $backup['date']->format('Y-m-d H:i:s')));
		}
	}

}
