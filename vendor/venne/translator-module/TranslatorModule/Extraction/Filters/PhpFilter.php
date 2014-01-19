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
use Nette\Reflection\ClassType;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PhpFilter extends BaseFilter
{

	/** @var array */
	protected $search = array(
		'Nette\Forms\Form' => array(
			'addButton' => 2,
			'addCheckbox' => 2,
			'addError' => 1,
			'addFile' => 2,
			'addGroup' => 1,
			'addImage' => 3,
			'addmultiSelect' => 2,
			'addPassword' => 2,
			'addRadioList' => 2,
			'addSelect' => 2,
			'addSubmit' => 2,
			'addText' => 2,
			'addTextArea' => 2,
		),
		'Nette\Object' => array(
			'_' => 1,
		),
	);


	/**
	 * @param $file
	 * @return array
	 */
	public function extract($file)
	{
		$ret = array();

		if (substr($file, -4) !== '.php') {
			return NULL;
		}

		$content = file_get_contents($file);

		$classes = $this->findClasses($content);
		foreach ($classes as $class) {
			if (class_exists($class)) {
				$ref = ClassType::from($class);

				foreach ($this->search as $key => $items) {
					if ($ref->isSubclassOf($key)) {
						foreach ($items as $method => $position) {
							$ret = array_merge($this->findMethodArg($content, $method, $position), $ret);
						}
					}
				}
			}
		}

		return $ret;
	}


	/**
	 * Find classes
	 *
	 * @param $content
	 * @return array
	 */
	protected function findClasses($content)
	{
		$classes = array();
		$tokens = token_get_all($content);
		$count = count($tokens);
		$ns = '';

		for ($i = 2; $i < $count; $i++) {

			// namespace
			if ($tokens[$i - 2][0] == T_NAMESPACE
				&& $tokens[$i - 1][0] == T_WHITESPACE
				&& $tokens[$i][0] == T_STRING
			) {
				$ns = $tokens[$i][1];

				while ($tokens[$i + 1][0] == 384 && $tokens[$i + 2][0]) {
					$ns = $ns . '\\' . $tokens[$i + 2][1];
					$i += 2;
				}
			}

			// class
			if ($tokens[$i - 2][0] == T_CLASS
				&& $tokens[$i - 1][0] == T_WHITESPACE
				&& $tokens[$i][0] == T_STRING
			) {

				$class_name = $tokens[$i][1];
				$classes[] = $ns . '\\' . $class_name;
			}
		}
		return $classes;
	}


	/**
	 * Find argument of method.
	 *
	 * @param $content
	 * @param $method
	 * @param int $position
	 * @return array
	 */
	protected function findMethodArg($content, $method, $position = 1)
	{
		$ret = array();
		$tokens = token_get_all($content);
		$count = count($tokens);
		$ns = '';

		for ($i = 2; $i < $count; $i++) {
			// arg
			if ($tokens[$i - 1][0] == T_STRING
				&& $tokens[$i - 1][1] == $method
				&& $tokens[$i] == '('
			) {
				$i += 1 + ($position - 1) * 2;

				if (isset($tokens[$i][1]) && strlen($tokens[$i][1]) > 2) {
					$ret[] = $a = substr($tokens[$i][1], 1, -1);
					if($a == 'route: '){
						die('ok');
					}
				}
			}
		}
		return $ret;
	}
}
