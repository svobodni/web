<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace HybridauthModule\Administration;

use CmsModule\Administration\Presenters\BasePresenter;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @secured
 */
class HybridauthPresenter extends BasePresenter
{

	/** @var HybridauthFormFactory */
	private $hybridauthFormFactory;


	/**
	 * @param \HybridauthModule\Administration\HybridauthFormFactory $hybridauthFormFactory
	 */
	public function injectHybridauthFormFactory(HybridauthFormFactory $hybridauthFormFactory)
	{
		$this->hybridauthFormFactory = $hybridauthFormFactory;
	}


	/**
	 * @secured(privilege="show")
	 */
	public function actionDefault()
	{
	}


	protected function createComponentForm()
	{
		$form = $this->hybridauthFormFactory->invoke();
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
