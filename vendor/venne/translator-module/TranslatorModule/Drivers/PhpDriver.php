<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TranslatorModule\Drivers;

use Venne;
use Nette\Config\Adapters\PhpAdapter;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PhpDriver extends BaseDriver
{

	/**
	 * @param string $lang
	 */
	public function save($data)
	{
		file_put_contents($this->file, $this->getAdapter()->dump($data));
	}


	/**
	 * @return string
	 */
	public function load()
	{
		return $this->getAdapter()->load($this->file);
	}


	/**
	 * @return PhpAdapter
	 */
	protected function getAdapter()
	{
		return new PhpAdapter();
	}
}
