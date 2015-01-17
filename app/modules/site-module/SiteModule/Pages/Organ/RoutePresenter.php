<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Organ;

use CmsModule\Content\Presenters\PagePresenter;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use SiteModule\Api\ApiClientFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @method \SiteModule\Pages\Organ\PageEntity getExtendedPage()
 */
class RoutePresenter extends PagePresenter
{
	const CACHE_NAMESPACE = 'Svobodni.Organ';

	/** @var \SiteModule\Api\ApiClientFactory */
	private $apiClientFactory;

	/** @var \SiteModule\Api\ApiClient */
	private $apiClient;

	/** @var \Nette\Caching\IStorage */
	private $cacheStorage;

	public function __construct(
		ApiClientFactory $apiClientFactory,
		IStorage $cacheStorage
	)
	{
		$this->apiClientFactory = $apiClientFactory;
		$this->cacheStorage = $cacheStorage;
	}

	public function renderDefault()
	{
		$cache = new Cache($this->cacheStorage, static::CACHE_NAMESPACE . '-' . $this->pageId);
		$values = $cache->load($this->getExtendedPage()->getSection());

		if ($values === null) {
			$values = new \stdClass();
			$values->data = $this->getData();

			$cache->save($this->getExtendedPage()->getSection(), $values, array(
				Cache::EXPIRE => '30 minutes',
			));
		}

		$this->template->data = $values->data;
	}

	/**
	 * @return mixed[]|null
	 */
	private function getData()
	{
		$data = $this->getApiClient()->callApi('/bodies.json');

		foreach ($data['bodies'] as $organ) {
			if ($organ['id'] === $this->getExtendedPage()->getSection()) {
				return $organ;
			}
		}
	}

	/**
	 * @return \SiteModule\Api\ApiClient
	 */
	private function getApiClient()
	{
		if ($this->apiClient === null) {
			$this->apiClient = $this->apiClientFactory->create($this->getExtendedPage()->getApiUrl());
		}

		return $this->apiClient;
	}

}
