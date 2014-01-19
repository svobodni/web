<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DeploymentModule;

use Doctrine\DBAL\Connection;
use Nette\InvalidArgumentException;
use Nette\Object;
use Nette\Utils\Finder;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class DeploymentManager extends Object
{

	/** @var Connection */
	private $connection;

	/** @var string */
	private $deploymentDir;


	/**
	 * @param $deploymentDir
	 * @param Connection $connection
	 */
	public function __construct($deploymentDir, Connection $connection)
	{
		$this->deploymentDir = $deploymentDir;
		$this->connection = $connection;
	}


	/**
	 * @param string $name
	 * @param bool $force
	 * @throws \Nette\InvalidArgumentException
	 */
	public function createBackup($name = '', $force = FALSE)
	{
		foreach (Finder::findFiles(($name ? : '*') . '@*@*')->in($this->getDeploymentDir()) as $file) {
			if (!$force) {
				throw new InvalidArgumentException("Backup '$name' already exists.");
			}

			unlink($file->getPathname());
		}

		$date = new \DateTime;
		$file = $this->getDeploymentDir() . '/' . $name . '@' . $this->getConnection()->getDriver()->getName() . '@' . $date->format("Y-m-d_H:i:s") . '.sql';

		system("mysqldump -u {$this->getConnection()->getUsername()} -p{$this->getConnection()->getPassword()} {$this->getConnection()->getDatabase()} > {$file}");
	}


	/**
	 * @param string $name
	 * @throws \Nette\InvalidArgumentException
	 */
	public function removeBackup($name = '')
	{
		foreach (Finder::findFiles(($name ? : '*') . '@*@*')->in($this->getDeploymentDir()) as $file) {
			unlink($file->getPathname());
			return;
		}

		throw new InvalidArgumentException("Backup '$name' does not exist.");
	}


	public function clearBackup()
	{
		foreach (Finder::findFiles('*@*@*')->in($this->getDeploymentDir()) as $file) {
			unlink($file->getPathname());
		}
	}


	/**
	 * @param string $name
	 * @throws \Nette\InvalidArgumentException
	 */
	public function loadBackup($name = '')
	{
		foreach (Finder::findFiles(($name ? : '*') . '@*@*')->in($this->getDeploymentDir()) as $file) {
			$sql = 'SET FOREIGN_KEY_CHECKS=0;' . file_get_contents($file->getPathname()) . '; SET FOREIGN_KEY_CHECKS=1;';
			$this->getConnection()->exec($sql);
			return;
		}

		throw new InvalidArgumentException("Backup '$name' does not exist.");
	}


	/**
	 * @return array
	 */
	public function getBackups()
	{
		$ret = array();

		foreach (Finder::findFiles('*@*@*')->in($this->getDeploymentDir()) as $file) {
			$file = explode('@', $file->getBasename('.sql'));
			$date = \DateTime::createFromFormat('Y-m-d_H:i:s', $file[2]);
			$ret[$file[0]] = array(
				'driver' => $file[1],
				'date' => $date,
				'timestamp' => $date->getTimestamp(),
			);
		}

		uasort($ret, function ($a, $b) {
			return $b['timestamp'] - $a['timestamp'];
		});

		return $ret;
	}


	/**
	 * @return string
	 */
	public function getDeploymentDir()
	{
		if (!file_exists($this->deploymentDir)) {
			mkdir($this->deploymentDir, 0777, TRUE);
		}

		return $this->deploymentDir;
	}


	/**
	 * @return Connection
	 */
	protected function getConnection()
	{
		return $this->connection;
	}

}
