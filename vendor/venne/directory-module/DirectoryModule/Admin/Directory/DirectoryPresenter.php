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

use CmsModule\Administration\AdminPresenter;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 *
 * @secured
 */
class DirectoryPresenter extends AdminPresenter
{

	/** @var PersonTableFactory */
	private $personTableFactory;



	/**
	 * @param PersonTableFactory $personTableFactory
	 */
	public function inject(
		PersonTableFactory $personTableFactory
	)
	{
		$this->personTableFactory = $personTableFactory;
	}


	protected function createComponentTable()
	{
		$control = $this->personTableFactory->invoke();
		return $control;
	}

}
