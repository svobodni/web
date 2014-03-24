<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DirectoryModule\Admin\Directory;

use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use Nette\Localization\ITranslator;
use Venne\BaseFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PersonTableFactory extends BaseFactory
{


	/** @var PersonRepository */
	private $personRepository;

	/** @var PersonFormFactory */
	private $personFormFactory;

	/** @var ITranslator */
	private $translator;


	/**
	 * @param PersonRepository $personRepository
	 * @param PersonFormFactory $personFormFactory
	 * @param ITranslator $translator
	 */
	public function __construct(PersonRepository $personRepository, PersonFormFactory $personFormFactory, ITranslator $translator = NULL)
	{
		$this->personRepository = $personRepository;
		$this->personFormFactory = $personFormFactory;
		$this->translator = $translator;
	}


	public function invoke()
	{
		$translator = $this->translator;

		$admin = new AdminGrid($this->personRepository);
		$table = $admin->getTable();
		$table->setTranslator($this->translator);

		$table->addColumnText('name', 'Name')
			->getCellPrototype()->style[] = 'width: 70%;';

		$table->addColumnText('type', 'Person')
			->setCustomRender(function (PersonEntity $personEntity) use ($translator) {
				$types = PersonEntity::getTypes();
				return $translator->translate($types[$personEntity->getType()]);
			})
			->getCellPrototype()->style[] = 'width: 30%;';

		$table->addAction('edit', 'Edit')
			->getElementPrototype()->class[] = 'ajax';

		$form = $admin->createForm($this->personFormFactory, 'Payment', NULL, \CmsModule\Components\Table\Form::TYPE_FULL);

		$admin->connectFormWithAction($form, $table->getAction('edit'), $admin::MODE_PLACE);

		// Toolbar
		$toolbar = $admin->getNavbar();
		$toolbar->addSection('new', 'Create', 'file');
		$admin->connectFormWithNavbar($form, $toolbar->getSection('new'), $admin::MODE_PLACE);

		$table->addAction('delete', 'Delete')
			->getElementPrototype()->class[] = 'ajax';
		$admin->connectActionAsDelete($table->getAction('delete'));

		return $admin;
	}

}
