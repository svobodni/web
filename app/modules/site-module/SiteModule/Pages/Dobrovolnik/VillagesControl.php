<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Dobrovolnik;

use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use CmsModule\Content\SectionControl;
use CmsModule\Pages\Users\UserEntity;
use Grido\DataSources\Doctrine;
use Nette\Utils\Html;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class VillagesControl extends SectionControl
{

	/** @var VillageRepository */
	protected $villageRepository;

	/** @var VillageFormFactory */
	protected $formFactory;


	/**
	 * @param VillageRepository $villageRepository
	 * @param VillageFormFactory $formFactory
	 */
	public function __construct(VillageRepository $villageRepository, VillageFormFactory $formFactory)
	{
		parent::__construct();

		$this->villageRepository = $villageRepository;
		$this->formFactory = $formFactory;
	}


	protected function createComponentTable()
	{
		$admin = new AdminGrid($this->villageRepository);

		// columns
		$table = $admin->getTable();

		$table->addColumnText('name', 'Name')
			->setSortable()
			->getCellPrototype()->width = '50%';
		$table->getColumn('name')
			->setFilterText()->setSuggestion();

		$table->addColumnText('latitude', 'Latitude');
		$table->addColumnText('longitude', 'Longitude');


		$formFactory = $this->formFactory;
		$form = $admin->createForm($formFactory, '', NULL, \CmsModule\Components\Table\Form::TYPE_FULL);

		$section = $admin->getNavbar()->addSection('new', 'New', 'item');

		$table->addAction('edit', 'Edit');
		$admin->connectFormWithAction($form, $table->getAction('edit'), $admin::MODE_PLACE);
		$admin->connectFormWithNavbar($form, $section, $admin::MODE_PLACE);

		return $admin;
	}

	public function render()
	{
		$this->template->render();
	}
}
