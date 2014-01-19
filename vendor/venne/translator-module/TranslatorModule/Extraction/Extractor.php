<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TranslatorModule\Extraction;

use Venne;
use Nette\Object;
use Nette\Utils\Finder;
use TranslatorModule\Extraction\Filters\IFilter;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Extractor extends Object
{

	/** @var IFilter[] */
	protected $filters = array();


	/**
	 * @param IFilter $filter
	 */
	public function addFilter(IFilter $filter)
	{
		$this->filters[] = $filter;
	}


	/**
	 * Extract all strings from path.
	 *
	 * @param $path
	 * @throws \Nette\InvalidArgumentException
	 * @return array
	 */
	public function extract($path)
	{
		if (!file_exists($path)) {
			throw new \Nette\InvalidArgumentException("Path '{$path} does not exists.'");
		}

		$data = array();

		foreach (Finder::findFiles('*')->from($path) as $file) {
			foreach ($this->filters as $filter) {
				if (($items = $filter->extract($file->getPathname())) !== NULL) {
					foreach ($items as $item) {
						$data[] = $item;
					}
				}
			}
		}

		foreach($data as $key=>$val) {
			$data[$key] = lcfirst($val);
		}

		$data = array_unique($data);

		return $data;
	}
}
