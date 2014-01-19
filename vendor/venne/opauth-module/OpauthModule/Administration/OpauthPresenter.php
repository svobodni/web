<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace OpauthModule\Administration;

use CmsModule\Administration\Presenters\BasePresenter;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @secured
 */
class OpauthPresenter extends BasePresenter
{

	/** @var OpauthFormFactory */
	private $opauthFormFactory;


	/**
	 * @param \OpauthModule\Administration\OpauthFormFactory $opauthFormFactory
	 */
	public function injectOpauthFormFactory(OpauthFormFactory $opauthFormFactory)
	{
		$this->opauthFormFactory = $opauthFormFactory;
	}


	/**
	 * @secured(privilege="show")
	 */
	public function actionDefault()
	{
	}


	protected function createComponentForm()
	{
		$form = $this->opauthFormFactory->invoke();
		$form->onSuccess[] = $this->formSuccess;
		return $form;
	}


	public function formSuccess(Form $form)
	{
		if ($form->isSubmitted() !== $form->getSaveButton()) {
			return;
		}

		$this->flashMessage($this->translator->translate('Configuration has been saved.'), 'success');

		if (!$this->isAjax()) {
			$this->redirect('this');
		}
	}
}
