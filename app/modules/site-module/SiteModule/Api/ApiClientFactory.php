<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Api;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ApiClientFactory extends \Nette\Object
{

	/**
	 * @param string $apiUrl
	 * @return \SiteModule\Api\ApiClient
	 */
	public function create($apiUrl)
	{
		return new ApiClient($apiUrl);
	}

}
