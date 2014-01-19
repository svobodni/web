<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace MailformModule\Pages\Mailform;

use CmsModule\Content\Presenters\PagePresenter;
use Nette\Callback;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class RoutePresenter extends PagePresenter
{

	/** @var Callback */
	protected $mailControlFactory;


	/**
	 * @param \Nette\Callback $mailControlFactory
	 */
	public function __construct(Callback $mailControlFactory)
	{
		parent::__construct();

		$this->mailControlFactory = $mailControlFactory;
	}


	/**
	 * @return MailControl
	 */
	protected function createComponentForm()
	{
		/** @var $control MailControl */
		$control = $this->mailControlFactory->invoke($this->extendedPage->mailform);
		$control->onSuccess[] = $this->formSuccess;
		return $control;
	}


	public function formSuccess()
	{
		$this->flashMessage($this->translator->translate('Message has been sent.'), 'success');
		$this->redirect('this');
	}
}
