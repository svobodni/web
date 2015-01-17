<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Fio;

use CmsModule\Content\Presenters\PagePresenter;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\InvalidStateException;
use SiteModule\Api\ApiClientFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @method \SiteModule\Pages\Fio\PageEntity getExtendedPage()
 */
class RoutePresenter extends PagePresenter
{

	const CACHE_NAMESPACE = 'Svobodni.Fio';

	/** @var \Nette\Caching\IStorage */
	private $cacheStorage;

	/** @var \SiteModule\Pages\Fio\PageService */
	private $pageService;

	public function __construct(
		PageService $pageService,
		IStorage $cacheStorage
	)
	{
		$this->cacheStorage = $cacheStorage;
		$this->pageService = $pageService;
	}

	public function renderDefault()
	{
		$cache = new Cache($this->cacheStorage, static::CACHE_NAMESPACE . '-' . $this->pageId);
		$values = $cache->load('values');

		if ($values === null) {
			try {
				$values = $this->pageService->getTransfers($this->getExtendedPage()->getAccountNumber());
			} catch (InvalidStateException $e) {
				$this->flashMessage('Nepodařilo se načíst data z transparentního účtu, zkuste to za chvíli znovu.');

				return;
			}

			$cache->save('values', $values,	array(
					Cache::EXPIRE => '30 minutes',
			));
		}

		$this->template->transfers = $values['transfers'];
		$this->template->state = $values['state'];
	}

}
