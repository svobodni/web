<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace OpauthModule\Security\SocialLogins;

use CmsModule\Security\Entities\LoginProviderEntity;
use CmsModule\Security\LoginProviders\BaseLoginProvider;
use Nette\Diagnostics\Debugger;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Utils\Strings;
use OpauthModule\Opauth;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractLogin extends BaseLoginProvider
{


	/** @var \Opauth */
	private $opauth;


	protected function getOpauthType()
	{
		return static::getType();
	}


	/**
	 * @return LoginProviderEntity
	 * @throws \Nette\InvalidStateException
	 * @throws \Nette\InvalidArgumentException
	 */
	protected function createLoginProviderEntity()
	{
		$type = $this->getOpauthType();

		$params = array(
			'path' => $this->getCallbackUrl(),
			'callback_url' => $this->getCallbackUrl(),
			'Strategy' => array(
				$type => $this->getConfig(),
			),
		);

		Debugger::$maxLen = 10000;

		$this->opauth = new Opauth($params, FALSE);
		$this->opauth->strategy = Strings::lower($type);

		$response = null;

		switch ($this->opauth->env['callback_transport']) {
			case 'session':
				if (isset($_SESSION['opauth'])) {
					$response = $_SESSION['opauth'];
					unset($_SESSION['opauth']);
				} else {
					$response = NULL;
				}
				break;
			case 'post':
				$response = unserialize(base64_decode($_POST['opauth']));
				break;
			case 'get':
				$response = unserialize(base64_decode($_GET['opauth']));
				break;
			default:
				throw new InvalidArgumentException("Unsupported callback transport.");
				break;
		}

		if ($response === NULL) {
			if (isset($_GET['_opauth_action'])) {
				$action = explode('/', $_GET['_opauth_action']);
				$this->opauth->action = $action[1];
			}

			$this->opauth->run();
		}

		if (array_key_exists('error', $response)) {
			throw new InvalidStateException($response['error']['message']);
		}

		if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])) {
			throw new InvalidStateException('Invalid auth response: Missing key auth response components');
		} elseif (!$this->opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)) {
			throw new InvalidStateException('Invalid auth response: ' . $reason);
		}

		$ret = new LoginProviderEntity($response['auth']['uid'], static::getType());
		$this->fillLoginEntity($ret, $response['auth']['raw']);
		return $ret;
	}


	protected function fillLoginEntity(LoginProviderEntity $entity, $raw)
	{

	}


	protected function getCallbackUrl()
	{
		$url = $_SERVER['REQUEST_URI'];
		$url = explode('?', $url, 2);
		//$url[1] = rtrim($url[1], '&_opauth_action=1');
		return rtrim($url[0], '/') . '?' . $url[1] . '&_opauth_action=1';
	}


	/**
	 * @return array
	 */
	protected function getConfig()
	{
		return array();
	}

}
