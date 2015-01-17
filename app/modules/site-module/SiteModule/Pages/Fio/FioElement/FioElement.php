<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Fio\FioElement;

use CmsModule\Content\Elements\BaseElement;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\InvalidStateException;
use SiteModule\Pages\Fio\PageService;
use Venne\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FioElement extends BaseElement
{
	const CACHE_NAMESPACE = 'Svobodni.Fio.element';

	/** @var TextFormFactory */
	protected $setupFormFactory;

	/** @var \Nette\Caching\IStorage */
	private $cacheStorage;

	/** @var \SiteModule\Pages\Fio\PageService */
	private $pageService;

	/**
	 * @param TextFormFactory $setupForm
	 */
	public function injectSetupForm(FormFactory $setupForm)
	{
		$this->setupFormFactory = $setupForm;
	}

	public function injectPrimary(
		PageService $pageService,
		IStorage $cacheStorage
	)
	{
		$this->cacheStorage = $cacheStorage;
		$this->pageService = $pageService;
	}

	/**
	 * @return array
	 */
	public function getViews()
	{
		return array(
			'setup' => 'Edit element',
		) + parent::getViews();
	}


	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return __NAMESPACE__ . '\FioEntity';
	}


	public function renderDefault()
	{
		if ($this->getExtendedElement()->getPage() === null) {
			$this->template->warning = true;
			return;
		}

		$cache = new Cache($this->cacheStorage, static::CACHE_NAMESPACE . '-' . $this->getExtendedElement()->getPage()->getId());
		$values = $cache->load('values');

		if ($values === null) {
			try {
				$values = $this->pageService->getTransfers(
					$this->getExtendedElement()->getPage()->getAccountNumber(),
					$this->getExtendedElement()->getItemsPerPage(),
					null,
					null
				);
			} catch (InvalidStateException $e) {
				$this->template->error = true;
				return;
			}

			$cache->save('values', $values,	array(
				Cache::EXPIRE => '30 minutes',
			));
		}

		$this->template->transfers = $values['transfers'];
		$this->template->state = $values['state'];
	}


	public function renderSetup()
	{
		echo $this['form']->render();
	}


	/**
	 * @return \Venne\Forms\Form
	 */
	protected function createComponentForm()
	{
		$form = $this->setupFormFactory->invoke($this->getExtendedElement());
		$form->onSuccess[] = function () {
			$this->getPresenter()->redirect('refresh!');
		};

		return $form;
	}

}
