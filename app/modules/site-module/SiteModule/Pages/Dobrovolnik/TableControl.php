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

use BlogModule\Pages\Blog\AbstractTableControl;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class TableControl extends AbstractTableControl
{

	/** @var DobrovolnikRepository */
	protected $articleRepository;

	/** @var DobrovolnikAdminFormFactory */
	protected $formFactory;


	/**
	 * @param DobrovolnikRepository $articleRepository
	 * @param DobrovolnikFormFactory $formFactory
	 */
	public function __construct(DobrovolnikRepository $articleRepository, DobrovolnikAdminFormFactory $formFactory)
	{
		parent::__construct();

		$this->articleRepository = $articleRepository;
		$this->formFactory = $formFactory;
	}


	protected function getRepository()
	{
		return $this->articleRepository;
	}


	protected function getFormFactory()
	{
		return $this->formFactory;
	}
}
