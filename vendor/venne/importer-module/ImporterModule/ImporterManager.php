<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace ImporterModule;

use Nette\InvalidArgumentException;
use Nette\Object;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @property-read BaseImporter[] $importers
 */
class ImporterManager extends Object
{

	/** @var BaseImporter[] */
	private $importers = array();


	/**
	 * @param $name
	 * @param BaseImporter $importer
	 * @throws InvalidArgumentException
	 */
	public function addImporter($name, BaseImporter $importer)
	{
		if (isset($this->importers[$name])) {
			throw new InvalidArgumentException("Name '$name' already exists.");
		}

		$this->importers[$name] = $importer;
	}


	/**
	 * @return BaseImporter[]
	 */
	public function getImporters()
	{
		return $this->importers;
	}


	/**
	 * @param $name
	 * @return BaseImporter
	 * @throws InvalidArgumentException
	 */
	public function getImporter($name)
	{
		if (!isset($this->importers[$name])) {
			throw new InvalidArgumentException("Name '$name' does not exist.");
		}

		return $this->importers[$name];
	}
}
