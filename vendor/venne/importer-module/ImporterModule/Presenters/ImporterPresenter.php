<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace ImporterModule\Presenters;

use CmsModule\Administration\AdminPresenter;
use ImporterModule\BaseImporter;
use ImporterModule\ImporterManager;
use Nette\Application\BadRequestException;
use Nette\InvalidArgumentException;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @secured
 */
class ImporterPresenter extends AdminPresenter
{

	/** @persistent */
	public $type;

	/** @var ImporterManager */
	private $importerManager;


	/**
	 * @param ImporterManager $importerManager
	 */
	public function injectImporterManager(ImporterManager $importerManager)
	{
		$this->importerManager = $importerManager;
	}


	/**
	 * @return ImporterManager
	 */
	public function getImporterManager()
	{
		return $this->importerManager;
	}


	/**
	 * @secured(privilege="show")
	 */
	public function actionDefault()
	{
	}


	protected function startup()
	{
		parent::startup();

		if (!$this->type) {
			$importers = $this->importerManager->getImporters();
			if (count($importers)) {
				$this->type = key($this->importerManager->getImporters());
			} else {
				$this->flashMessage($this->translator->translate('No importer found.'), 'info');
			}
		}
	}


	protected function createComponentForm()
	{
		/** @var Form $form */
		$form = $this->getImporter()->getFormFactory()->invoke();

		if (!$form->hasSaveButton()) {
			$form->addSaveButton('Run import');
		} else {
			$form->getSaveButton()->caption = 'Run import';
		}

		$form->onSuccess[] = $this->formSuccess;
		return $form;
	}


	public function formSuccess(Form $form)
	{
		if ($form->isSubmitted() !== $form->getSaveButton()) {
			return;
		}

		$this->getImporter()->run($form);

		$this->flashMessage($this->translator->translate('Process has been finished'), 'success');
		$this->redirect('this');
	}


	/**
	 * @return BaseImporter
	 * @throws BadRequestException
	 */
	private function getImporter()
	{
		try {
			return $this->importerManager->getImporter($this->type);
		} catch (InvalidArgumentException $e) {
			throw new BadRequestException;
		}
	}
}
