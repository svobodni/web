<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Fio;

use DateTime;
use SiteModule\Api\ApiClientFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PageService extends \Nette\Object
{

	const FIO_API_URL = 'https://www.fio.cz/scgi-bin/hermes/dz-transparent.cgi';

	/** @var \SiteModule\Api\ApiClientFactory */
	private $apiClientFactory;

	/** @var \SiteModule\Api\ApiClient */
	private $apiClient;

	public function __construct(
		ApiClientFactory $apiClientFactory
	)
	{
		$this->apiClientFactory = $apiClientFactory;
	}

	/**
	 * @param string $accountNumber
	 * @param int|null $maxItems
	 * @param \DateTime|null $dateFrom
	 * @param \DateTime|null $dateTo
	 * @return \mixed[]
	 */
	public function getTransfers($accountNumber, $maxItems = null, DateTime $dateFrom = null, DateTime $dateTo = null)
	{
		$data = $this->getRawData($accountNumber, $dateFrom, $dateTo);

		$transferData = substr($data, strpos($data, '<table class=\'main\'>'));
		$transferData = substr($transferData, 0, strpos($transferData, '</table>') + 8);

		$dom = new \DOMDocument();
		$dom->loadHTML($transferData);

		$finder = new \DomXPath($dom);
		$nodes = $finder->query('//table[@class="main"]//tbody//tr');

		$transfers = array();
		/** @var \DOMElement $node */
		$i = 1;
		$length = $nodes->length;
		foreach ($nodes as $node) {
			$value = str_replace(',', '.', trim($node->childNodes->item(2)->nodeValue));
			$value = (float)str_replace(' ', '', $value);

			$transfers[] = array(
				\DateTime::createFromFormat('d.m.Y', $node->childNodes->item(0)->nodeValue),
				$value,
				$node->childNodes->item(12)->nodeValue,
				$node->childNodes->item(14)->nodeValue,
			);

			if ($maxItems !== null && $i >= $maxItems) {
				break;
			}

			$i++;

			if ($i >= $length) {
				break;
			}
		}


		$stateData = substr($data, strpos($data, '<table class=\'summary\'>'));
		$stateData = substr($stateData, 0, strpos($stateData, '</table>') + 8);

		$dom = new \DOMDocument();
		$dom->loadHTML($stateData);

		$finder = new \DomXPath($dom);
		$nodes = $finder->query('//table[@class="summary"]//tbody//tr//td[2]');

		$value = trim($nodes->item(0)->nodeValue);
		$value = str_replace(',', '.', $value);

		return array(
			'state' => (float)str_replace(' ', '', $value),
			'transfers' => $transfers,
		);
	}

	/**
	 * @param int $accountNumber
	 * @param \DateTime|null $dateFrom
	 * @param \DateTime|null $dateTo
	 * @return string|null
	 */
	private function getRawData($accountNumber, DateTime $dateFrom = null, DateTime $dateTo = null)
	{
		if ($dateFrom === null) {
			$dateFrom = new DateTime('-1 month');
		}

		if ($dateTo === null) {
			$dateTo = new DateTime();
		}

		$data = $this->getApiClient()->callRawApi('?ID_ucet=' . $accountNumber);
		$data = iconv('windows-1250', 'UTF-8', $data);
		$data = mb_convert_encoding($data, 'HTML-ENTITIES', 'UTF-8');

		return $data;
	}

	/**
	 * @return \SiteModule\Api\ApiClient
	 */
	private function getApiClient()
	{
		if ($this->apiClient === null) {
			$this->apiClient = $this->apiClientFactory->create(static::FIO_API_URL);
		}

		return $this->apiClient;
	}

}
