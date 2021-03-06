<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CmsModule\Content\Routes;

use CmsModule\Content\Entities\RouteEntity;
use DoctrineModule\Repositories\BaseRepository;
use Nette\Application\Routers\Route;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ImageRoute extends Route
{

	public function __construct()
	{
		parent::__construct('public/media/_cache/<size>/<format>/<type>/<url .+>', array(
			'presenter' => 'Cms:File',
			'action' => 'image',
			'url' => array(
				self::VALUE => '',
				self::FILTER_IN => NULL,
				self::FILTER_OUT => NULL,
			)
		), Route::SECURED);
	}
}
