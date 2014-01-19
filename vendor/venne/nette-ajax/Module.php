<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace AjaxModule;

use Venne\Module\ComposerModule;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Module extends ComposerModule
{

	public function getName()
	{
		return 'ajax';
	}


	public function getVersion()
	{
		return '2.0.0';
	}


	public function getRelativePublicPath()
	{
		return '/client-side';
	}


	public function getConfiguration()
	{
		return array(
			'extensions' => array(
				'ajax' => 'VojtechDobes\NetteAjax\Extension',
			),
		);
	}
}
