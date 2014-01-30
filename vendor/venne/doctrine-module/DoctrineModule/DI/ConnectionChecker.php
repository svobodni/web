<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DoctrineModule\DI;

use Doctrine\ORM\EntityManager;
use Nette\Object;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ConnectionChecker extends Object
{

	/** @var bool */
	private $isConnected;

	/** @var EntityManager */
	private $entityManager;


	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}


	public function checkConnection()
	{
		if ($this->isConnected === NULL) {
			$ret = (object)array('val' => TRUE);
			$connection = $this->entityManager->getConnection();
			$old = set_error_handler(function () use ($ret) {
				$ret->val = FALSE;
			});

			try {
				$c = $connection->connect();
				if (!is_bool($c)) {
					$ret->val = FALSE;
				}

				$connection->getSchemaManager()->tablesExist('user'); // try connect with some sql
			} catch (\Exception $ex) {
				$ret->val = FALSE;
			}

			set_error_handler($old);
			$this->isConnected = $ret->val;
		}

		return $this->isConnected;
	}
}

