<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GalleryModule\Pages\Gallery;

use BlogModule\Pages\Blog\AbstractRoutePresenter;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class RoutePresenter extends AbstractRoutePresenter
{

	/** @var CategoryRepository */
	private $repository;


	/**
	 * @param CategoryRepository $repository
	 */
	public function injectRepository(CategoryRepository $repository)
	{
		$this->repository = $repository;
	}


	/**
	 * @return UserRepository
	 */
	protected function getRepository()
	{
		return $this->repository;
	}
}