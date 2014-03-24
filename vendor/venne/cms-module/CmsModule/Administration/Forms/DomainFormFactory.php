<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CmsModule\Administration\Forms;

use CmsModule\Content\Repositories\PageRepository;
use DoctrineModule\Forms\Controls\ManyToOne;
use DoctrineModule\Forms\FormFactory;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class DomainFormFactory extends FormFactory
{

	/** @var PageRepository */
	private $pageRepository;


	/**
	 * @param \CmsModule\Content\Repositories\PageRepository $pageRepository
	 */
	public function injectPageRepository(PageRepository $pageRepository)
	{
		$this->pageRepository = $pageRepository;
	}



	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addText('name', 'Name')
			->addRule($form::FILLED);
		$form->addText('domain', 'Domain')
			->addRule($form::FILLED);
		/** @var ManyToOne $page */
		$page = $form->addOneToOne('page', 'Page');
		$page->setQuery(
			$this->pageRepository->createQueryBuilder('a')
		->leftJoin('a.mainRoute', 'r')
		->andWhere('r.domain IS NULL')
		->orderBy('a.positionString', 'ASC')
		);

		$form->addSaveButton('Save');
	}

}
