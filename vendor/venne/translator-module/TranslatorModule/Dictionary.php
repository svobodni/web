<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TranslatorModule;

use Venne;
use Nette\Object;
use Nette\Utils\Finder;
use TranslatorModule\Drivers\IDriver;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Dictionary extends Object implements IDictionary
{

	/** @var string */
	protected $lang;

	/** @var string */
	protected $path;

	/** @var array */
	protected $data;


	/**
	 * @param $path
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}


	/**
	 * @param string $lang
	 */
	public function setLang($lang)
	{
		$this->lang = $lang;
	}


	/**
	 * Get data from files.
	 *
	 * @return array
	 */
	public function getData()
	{
		$data = array();

		foreach ($this->getFiles() as $file) {
			$fileInfo = new \SplFileInfo($file);
			$class = "\\TranslatorModule\\Drivers\\" . ucfirst($fileInfo->getExtension()) . "Driver";

			/** @var $driver IDriver */
			$driver = new $class($file);
			$data = $data + $driver->load();
		}

		return $data;
	}


	/**
	 * Get files with translations.
	 *
	 * @return array
	 * @throws \Nette\InvalidArgumentException
	 */
	public function getFiles()
	{
		if (!file_exists($this->path)) {
			throw new \Nette\InvalidArgumentException("Path '{$this->path}' does not exists.");
		}

		$files = array();

		foreach (Finder::findFiles("*.{$this->lang}.*")->in($this->path) as $file) {
			$files[] = $file->getPathname();
		}

		return $files;
	}
}
