<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TranslatorModule\Extraction\Filters;

use Venne;
use Nette\Object;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class BaseFilter extends Object implements IFilter
{

	/**
	 * @param $file
	 * @return array
	 */
	public function extract($file)
	{
		return array();
	}


	/**
	 * @param $content
	 * @param $start
	 * @param $end
	 * @return array
	 */
	protected function matchBetween($content, $start, $end)
	{
		$ret = array();

		if (preg_match_all("/{$start}(.*?){$end}/s", $content, $match)) {
			foreach ($match[1] as $item) {
				$ret[] = $item;
			}
		}

		return $ret;
	}
}
