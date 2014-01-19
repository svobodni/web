<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace OpauthModule;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Opauth extends \Opauth
{

	public $strategy;

	public $action;

	/**
	 * Run Opauth:
	 * Parses request URI and perform defined authentication actions based based on it.
	 */
	public function run() {
		$this->parseUri();

		if (!empty($this->env['params']['strategy'])) {
			if (strtolower($this->env['params']['strategy']) == 'callback') {
				$this->callback();
			} elseif (array_key_exists($this->env['params']['strategy'], $this->strategyMap)) {
				$name = $this->strategyMap[$this->env['params']['strategy']]['name'];
				$class = $this->strategyMap[$this->env['params']['strategy']]['class'];
				$strategy = $this->env['Strategy'][$name];

				// Strip out critical parameters
				$safeEnv = $this->env;
				unset($safeEnv['Strategy']);

				$actualClass = $this->requireStrategy($class);
				$this->Strategy = new $actualClass($strategy, $safeEnv);

				if (empty($this->env['params']['action'])) {
					$this->env['params']['action'] = 'request';
				}

				$this->Strategy->callAction($this->env['params']['action']);
			} else {
				trigger_error('Unsupported or undefined Opauth strategy - '.$this->env['params']['strategy'], E_USER_ERROR);
			}
		} else {
			$sampleStrategy = array_pop($this->env['Strategy']);
			trigger_error('No strategy is requested. Try going to '.$this->env['complete_path'].$sampleStrategy['strategy_url_name'].' to authenticate with '.$sampleStrategy['strategy_name'], E_USER_NOTICE);
		}
	}

	/**
	 * Parses Request URI
	 */
	private function parseUri() {
		$this->env['request'] = substr($this->env['request_uri'], strlen($this->env['path']) - 1);

		if (preg_match_all('/\/([A-Za-z0-9-_]+)/', $this->env['request'], $matches)) {
			foreach ($matches[1] as $match) {
				$this->env['params'][] = $match;
			}
		}

		$this->env['params']['strategy'] = $this->strategy;
		$this->env['params']['action'] = $this->action;
	}


	/**
	 * Loads a strategy, firstly check if the
	 *  strategy's class already exists, especially for users of Composer;
	 * If it isn't, attempts to load it from $this->env['strategy_dir']
	 *
	 * @param string $strategy Name of a strategy
	 * @return string Class name of the strategy, usually StrategyStrategy
	 */
	private function requireStrategy($strategy) {
		if (!class_exists($strategy.'Strategy')) {
			// Include dir where Git repository for strategy is cloned directly without
			// specifying a dir name, eg. opauth-facebook
			$directories = array(
				$this->env['strategy_dir'].$strategy.'/',
				$this->env['strategy_dir'].'opauth-'.strtolower($strategy).'/',
				$this->env['strategy_dir'].strtolower($strategy).'/',
				$this->env['strategy_dir'].'Opauth-'.$strategy.'/'
			);

			// Include deprecated support for strategies without Strategy postfix as class name or filename
			$classNames = array(
				$strategy.'Strategy',
				$strategy
			);

			$found = false;
			foreach ($directories as $dir) {
				foreach ($classNames as $name) {
					if (file_exists($dir.$name.'.php')) {
						$found = true;
						require $dir.$name.'.php';
						return $name;
					}
				}
			}

			if (!$found) {
				trigger_error('Strategy class file ('.$this->env['strategy_dir'].$strategy.'/'.$strategy.'Strategy.php'.') is missing', E_USER_ERROR);
			}
		}
		return $strategy.'Strategy';
	}

}
