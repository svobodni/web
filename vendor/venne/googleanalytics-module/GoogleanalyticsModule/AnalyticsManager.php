<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule;

use Nette\Object;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AnalyticsManager extends Object
{

	/** @var bool */
	protected $activated;

	/** @var string */
	protected $accountId;

	/** @var bool */
	protected $apiActivated;

	/** @var string */
	protected $applicationName;

	/** @var string */
	protected $clientId;

	/** @var string */
	protected $clientMail;

	/** @var int */
	protected $gaId;

	/** @var string */
	protected $keyFile;

	/** @var \Google_Client */
	protected $_googleClient;

	/** @var \Google_AnalyticsService */
	protected $_analyticsService;

	/** @var string */
	protected $googleanalyticsPath;


	public function __construct($activated, $accountId, $apiActivated, $applicationName, $clientId, $clientMail, $gaId, $keyFile, $googleanalyticsPath)
	{
		$this->accountId = $accountId;
		$this->activated = $activated;
		$this->apiActivated = $apiActivated;
		$this->applicationName = $applicationName;
		$this->clientId = $clientId;
		$this->clientMail = $clientMail;
		$this->gaId = $gaId;
		$this->keyFile = $keyFile;
		$this->googleanalyticsPath = $googleanalyticsPath;

		include_once $this->googleanalyticsPath . '/src/Google_Client.php';
		include_once $this->googleanalyticsPath . '/src/contrib/Google_AnalyticsService.php';
	}


	/**
	 * @return string
	 */
	public function getAccountId()
	{
		return $this->accountId;
	}


	/**
	 * @return boolean
	 */
	public function getActivated()
	{
		return $this->activated;
	}


	/**
	 * @return boolean
	 */
	public function getApiActivated()
	{
		return $this->apiActivated;
	}


	/**
	 * @return string
	 */
	public function getApplicationName()
	{
		return $this->applicationName;
	}


	/**
	 * @return string
	 */
	public function getClientId()
	{
		return $this->clientId;
	}


	/**
	 * @return string
	 */
	public function getClientMail()
	{
		return $this->clientMail;
	}


	/**
	 * @return int
	 */
	public function getGaId()
	{
		return $this->gaId;
	}


	/**
	 * @return string
	 */
	public function getKeyFile()
	{
		return $this->keyFile;
	}


	public function getGoogleAnalyticsService($redirectUrl)
	{
		if (!$this->_analyticsService) {
			$client = $this->getGoogleClient($redirectUrl);
			$this->_analyticsService = new \Google_AnalyticsService($client);
		}

		return $this->_analyticsService;
	}


	/**
	 * @return \Google_Client
	 */
	protected function getGoogleClient($redirectUrl)
	{
		if (!$this->_googleClient) {
			$this->_googleClient = new \Google_Client();
			$this->_googleClient->setUseObjects(TRUE);
			$this->_googleClient->setApplicationName($this->applicationName);
			$this->_googleClient->setAssertionCredentials(
				new \Google_AssertionCredentials(
					$this->clientMail,
					array('https://www.googleapis.com/auth/analytics.readonly'),
					file_get_contents($this->keyFile)
				)
			);
			$this->_googleClient->setClientId($this->clientId);
			$this->_googleClient->setRedirectUri($redirectUrl);
		}
		return $this->_googleClient;
	}
}
