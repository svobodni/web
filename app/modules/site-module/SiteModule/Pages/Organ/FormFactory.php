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

use DoctrineModule\Forms\Mappers\EntityMapper;
use SiteModule\Api\ApiClientFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormFactory extends \DoctrineModule\Forms\FormFactory
{

	/** @var \SiteModule\Api\ApiClientFactory */
	private $apiClientFactory;

	public function __construct(
		EntityMapper $mapper,
		ApiClientFactory $apiClientFactory
	)
	{
		parent::__construct($mapper);
		$this->apiClientFactory = $apiClientFactory;
	}


	public function configure(Form $form)
	{
		$form->addGroup('Nastavení API');
		$form->addText('apiUrl', 'URL pro api');

		if ($form->getData()->getApiUrl()) {
			$apiClient = $this->apiClientFactory->create($form->getData()->getApiUrl());
			$data = $apiClient->callApi('/bodies.json');
			$items = array();

			foreach ($data['bodies'] as $organ) {
				$items[$organ['id']] = $organ['name'];
			}

			$form->addSelect('section', 'Sekce', $items);
		}

		$form->setCurrentGroup();
		$form->addSaveButton('Save');
	}

}
