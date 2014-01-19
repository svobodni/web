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

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\InvalidArgumentException;
use Nette\Localization\ITranslator;
use Nette\Object;
use Nette\Utils\Strings;
use TranslatorModule\Drivers\IDriver;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Translator extends Object implements ITranslator
{

	const DEVELOPMENT = FALSE;

	const PRODUCTION = TRUE;

	/** @var string */
	protected $lang;

	/** @var Cache */
	protected $cache;

	/** @var IDictionary[] */
	protected $dictionaries = array();

	/** @var array */
	protected $data;

	/** @var bool */
	protected $mode;


	/**
	 * @param $mode
	 */
	public function __construct($mode = TRUE)
	{
		$this->mode = $mode;
	}


	/**
	 * @param IStorage $driver
	 */
	public function setCache(IStorage $cacheStorage)
	{
		$this->cache = new Cache($cacheStorage, 'Venne.Translator');
	}


	/**
	 * @param IDriver $driver
	 */
	public function addDictionary(IDictionary $driver)
	{
		$driver->setLang($this->lang);
		$this->dictionaries[] = $driver;
	}


	/**
	 * Translates the given string.
	 *
	 * @param  string   message
	 * @param  int      plural count
	 * @return string
	 */
	public function translate($message, $count = NULL, array $parameters = array())
	{
		$pluralization = $count !== NULL ? PluralizationRules::get($count, $this->lang) : 0;

		$lcMessage = lcfirst($message);
		$uc = ctype_upper(substr($message, 0, 1));
		$this->loadData();

		if (isset($this->data[$lcMessage])) {
			$ret = isset($this->data[$lcMessage][$pluralization]) ? $this->data[$lcMessage][$pluralization] : $this->data[$lcMessage][0];
		} else {
			$ret = explode('|', $message);
			$ret = isset($ret[$pluralization]) ? $ret[$pluralization] : $ret[0];
		}

		if ($count !== NULL) {
			$parameters += array('count' => $count);
		}

		foreach ($parameters as $key => $val) {
			$ret = str_replace('%' . $key . '%', $val, $ret);
		}

		if ($uc) {
			$ret = Strings::firstUpper($ret);
		}

		return $ret;
	}


	/**
	 * @param string $lang
	 */
	public function setLang($lang)
	{
		$this->lang = $lang;
		$this->data = NULL;

		foreach ($this->dictionaries as $item) {
			$item->setLang($this->lang);
		}
	}


	/**
	 * @return string
	 */
	public function getLang()
	{
		return $this->lang;
	}


	/**
	 * Load data into local memory.
	 */
	protected function loadData()
	{
		if ($this->data === NULL) {
			if ($this->cache) {
				if (($this->data = $this->loadFromCache()) === NULL) {
					$this->data = $this->getData();
					$this->saveToCache();
				}
			} else {
				$this->data = $this->getData();
			}
		}
	}


	/**
	 * Get data from dictionaries.
	 *
	 * @return array
	 */
	protected function getData()
	{
		$data = array();
		foreach ($this->dictionaries as $item) {
			$items = array();
			foreach ($item->getData() as $key => $val) {
				if (!is_scalar($val) && $val !== NULL) {
					throw new InvalidArgumentException("Value must be string.");
				}

				$items[$key] = explode('|', (string)$val);
			}
			$data = $data + $items;
		}
		return $data;
	}


	/**
	 * Get files from dictionaries.
	 *
	 * @return array
	 */
	protected function getFiles()
	{
		$files = array();
		foreach ($this->dictionaries as $item) {
			$files = array_merge($item->getFiles(), $files);
		}
		return $files;
	}


	/**
	 * Save data to cache.
	 */
	protected function saveToCache()
	{
		if ($this->mode == self::DEVELOPMENT) {
			$files = $this->getFiles();
			sort($files);

			$cacheKey = array($this->mode, $files, $this->lang);
			$this->cache->save($cacheKey, $this->data, array(
				Cache::FILES => $files,
			));
		} else {
			$this->cache->save($this->lang, $this->data);
		}
	}


	/**
	 * Load data from cache.
	 *
	 * @return array|NULL
	 */
	protected function loadFromCache()
	{
		if ($this->mode == self::DEVELOPMENT) {
			$files = $this->getFiles();
			sort($files);

			$cacheKey = array($this->mode, $files, $this->lang);
			return $this->cache->load($cacheKey);
		}
		return $this->cache->load($this->lang);
	}
}
