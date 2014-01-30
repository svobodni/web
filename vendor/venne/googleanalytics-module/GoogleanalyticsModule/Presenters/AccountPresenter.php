<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule\Presenters;

use CmsModule\Administration\Presenters\BasePresenter;
use GoogleanalyticsModule\Forms\AccountFormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @secured
 */
class AccountPresenter extends BasePresenter
{


	/** @var AccountFormFactory */
	protected $accountFormFactory;


	/**
	 * @param \GoogleanalyticsModule\Forms\AccountFormFactory $accountFormFactory
	 */
	public function injectAccountFormFactory(AccountFormFactory $accountFormFactory)
	{
		$this->accountFormFactory = $accountFormFactory;
	}


	/**
	 * @secured(privilege="show")
	 */
	public function actionDefault()
	{
	}


	protected function createComponentForm()
	{
		$form = $this->accountFormFactory->invoke();
		$form->onSuccess[] = $this->formSuccess;
		return $form;
	}


	public function formSuccess($form)
	{
		$this->flashMessage($this->translator->translate('Account has been saved.'), 'success');

		if (!$this->isAjax()) {
			$this->redirect('this');
		}
	}
}
