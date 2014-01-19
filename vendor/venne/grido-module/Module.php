<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GridoModule;

use Venne\Module\ComposerModule;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Module extends ComposerModule
{

	/**
	 * @return string
	 */
	public function getRelativePublicPath()
	{
		if (file_exists(__DIR__ . '/vendor/o5/grido/client-side')) {
			return '/vendor/o5/grido/client-side';
		}

		return '/../../o5/grido/client-side';
	}
}
