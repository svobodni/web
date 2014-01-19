<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DoctrineModule;

use Venne;
use Nette\Caching\IStorage;
use Doctrine\Common\Cache\CacheProvider;


/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @author Patrik Votoček
 */
class Cache extends CacheProvider
{

	const CACHE_TAG = 'doctrine';

	const CACHE_NAMESPACE = 'Venne.Doctrine';

	/** @var \Nette\Caching\Cache */
	private $storage = array();


	/**
	 * @param IStorage
	 * @param string
	 */
	public function  __construct(IStorage $cacheStorage, $name = self::CACHE_NAMESPACE)
	{
		$this->storage = new \Nette\Caching\Cache($cacheStorage, $name);
	}


	/**
	 * Fetches an entry from the cache.
	 *
	 * @param string    cache id The id of the cache entry to fetch.
	 * @return string    The cached data or FALSE, if no cache entry exists for the given id.
	 */
	protected function doFetch($id)
	{
		return $this->storage->load($id) ? : FALSE;
	}


	/**
	 * Test if an entry exists in the cache
	 *
	 * @param string    cache id The cache id of the entry to check for.
	 * @return boolean    TRUE if a cache entry exists for the given cache id, FALSE otherwise.
	 */
	protected function doContains($id)
	{
		return $this->storage->load($id) !== NULL;
	}


	/**
	 * Puts data into the cache
	 *
	 * @param string    The cache id.
	 * @param string    The cache entry/data.
	 * @param int    The lifetime. If != false, sets a specific lifetime for this cache entry (null => infinite lifeTime).
	 * @return boolean    TRUE if the entry was successfully stored in the cache, FALSE otherwise.
	 */
	protected function doSave($id, $data, $lifeTime = false)
	{
		$files = array();
		if ($data instanceof \Doctrine\ORM\Mapping\ClassMetadata) {
			$ref = \Nette\Reflection\ClassType::from($data->name);
			$files[] = $ref->getFileName();
			foreach ($data->parentClasses as $class) {
				$ref = \Nette\Reflection\ClassType::from($class);
				$files[] = $ref->getFileName();
			}
		}

		if ($lifeTime != 0) {
			$this->storage->save($id, $data, array(
				\Nette\Caching\Cache::EXPIRE => time() + $lifeTime,
				\Nette\Caching\Cache::TAGS => array(static::CACHE_TAG),
				\Nette\Caching\Cache::FILES => $files,
			));
		} else {
			$this->storage->save($id, $data, array(
				\Nette\Caching\Cache::TAGS => array(static::CACHE_TAG),
				\Nette\Caching\Cache::FILES => $files,
			));
		}

		return TRUE;
	}


	/**
	 * Deletes a cache entry.
	 *
	 * @param string    cache id
	 * @return boolean    TRUE if the cache entry was successfully deleted, FALSE otherwise.
	 */
	protected function doDelete($id)
	{
		$this->storage->save($id, NULL);
		return TRUE;
	}


	/**
	 * Deletes all cache entries
	 *
	 * @return boolean    TRUE if the cache entry was successfully deleted, FALSE otherwise.
	 */
	protected function doFlush()
	{
		$this->storage->clean(array(NCache::ALL => TRUE));
	}


	/**
	 * Retrieves cached information from data store
	 *
	 * @return  array    An associative array with server's statistics if available, NULL otherwise.
	 */
	protected function doGetStats()
	{
		return NULL; // @TODO
	}
}

