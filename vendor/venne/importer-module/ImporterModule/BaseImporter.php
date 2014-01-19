<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace ImporterModule;

use Doctrine\ORM\EntityManager;
use Nette\Object;
use Venne\Forms\Form;
use Venne\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class BaseImporter extends Object
{

	/** @var EntityManager */
	private $entityManager;

	/** @var FormFactory */
	private $formFactory;


	/**
	 * @param FormFactory $formFactory
	 * @param EntityManager $entityManager
	 */
	public function __construct(FormFactory $formFactory, EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->formFactory = $formFactory;
	}


	/**
	 * @return EntityManager
	 */
	protected function getEntityManager()
	{
		return $this->entityManager;
	}


	/**
	 * @return FormFactory
	 */
	public function getFormFactory()
	{
		return $this->formFactory;
	}


	abstract function run(Form $form);
}
