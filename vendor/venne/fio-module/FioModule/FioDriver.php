<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace FioModule;

use DirectoryModule\Admin\Directory\PersonEntity;
use DirectoryModule\Admin\Directory\PersonRepository;
use h4kuna\Fio;
use Nette\Forms\Container;
use Nette\Object;
use PaymentsModule\Admin\Payments\AccountEntity;
use PaymentsModule\Admin\Payments\AccountRepository;
use PaymentsModule\Admin\Payments\BankEntity;
use PaymentsModule\Admin\Payments\BankRepository;
use PaymentsModule\Admin\Payments\CurrencyEntity;
use PaymentsModule\Admin\Payments\CurrencyRepository;
use PaymentsModule\Admin\Payments\PaymentEntity;
use PaymentsModule\IDriver;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FioDriver extends Object implements IDriver
{

	/** @var Fio[] */
	private $_fio = array();

	/** @var string */
	private $tempDir;

	/** @var CurrencyRepository */
	private $currencyRepository;

	/** @var BankRepository */
	private $bankRepository;

	/** @var AccountRepository */
	private $accountRepository;

	/** @var PersonRepository */
	private $personRepository;


	/**
	 * @param $tempDir
	 * @param CurrencyRepository $currencyRepository
	 * @param BankRepository $bankRepository
	 * @param AccountRepository $accountRepository
	 * @param PersonRepository $personRepository
	 */
	public function __construct(
		$tempDir,
		CurrencyRepository $currencyRepository,
		BankRepository $bankRepository,
		AccountRepository $accountRepository,
		PersonRepository $personRepository
	)
	{
		$this->tempDir = $tempDir;
		$this->currencyRepository = $currencyRepository;
		$this->bankRepository = $bankRepository;
		$this->accountRepository = $accountRepository;
		$this->personRepository = $personRepository;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return 'Fio bank';
	}


	/**
	 * @return string
	 */
	public function getCode()
	{
		return '2010';
	}


	/**
	 * @param AccountEntity $accountEntity
	 * @param \DateTime $dateFrom
	 * @param \DateTime $dateTo
	 * @return \PaymentsModule\Admin\Payments\PaymentEntity[]
	 */
	public function getPayments(AccountEntity $accountEntity, \DateTime $dateFrom = NULL, \DateTime $dateTo = NULL)
	{
		$ret = array();

		foreach ($this->getFio($accountEntity)->movements($dateFrom) as $movement) {

			$ret[] = $entity = new PaymentEntity;
			$entity->setPaymentId($movement['moveId']);
			$entity->setDate($movement['moveDate']);
			$entity->setAmount($movement['amount']->getValue());

			$currency = $this->getCurrencyByName($movement['currency']);

			if (isset($movement['toAccount']) && isset($movement['bankCode'])) {
				$bankName = isset($movement['bankName']) && $movement['bankName'] ? $movement['bankName'] : NULL;
				$user = isset($movement['userNote']) && $movement['userNote'] ? $movement['userNote'] : NULL;
				$entity->setOffset($this->getAccountByNumber($movement['toAccount'], $currency, $movement['bankCode'], $user, $bankName));
			}

			if (isset($movement['userNote'])) {
				$entity->setUserIdentification($movement['userNote']);
			}

			if (isset($movement['message'])) {
				$entity->setMessage($movement['message']);
			}

			if (isset($movement['type'])) {
				$entity->setType($movement['type']);
			}

			if (isset($movement['comment'])) {
				$entity->setComment($movement['comment']);
			}

			if (isset($movement['instructionId'])) {
				$entity->setInstructionId($movement['instructionId']);
			}

			if (isset($movement['constantSymbol'])) {
				$entity->setConstantSymbol($movement['constantSymbol']);
			}

			if (isset($movement['variableSymbol'])) {
				$entity->setVariableSymbol($movement['variableSymbol']);
			}

			if (isset($movement['specificSymbol'])) {
				$entity->setSpecificSymbol($movement['specificSymbol']);
			}
		}

		return $ret;
	}


	/**
	 * @param Container $container
	 * @return Container
	 */
	public function configureOptionsContainer(Container $container)
	{
		$container->addText('token', 'Token');
		return $container;
	}


	/**
	 * @param AccountEntity $accountEntity
	 * @return Fio
	 */
	private function getFio(AccountEntity $accountEntity)
	{
		if (!isset($this->_fio[$accountEntity->getId()])) {
			$parameters = $accountEntity->getOptions();
			$this->_fio[$accountEntity->getId()] = new Fio(
				$parameters['token'],
				$accountEntity->getName() . '/' . $this->getCode(),
				$this->tempDir
			);
		}

		return $this->_fio[$accountEntity->getId()];
	}


	/**
	 * @param $name
	 * @return CurrencyEntity
	 */
	private function getCurrencyByName($name)
	{
		if (($entity = $this->currencyRepository->findOneBy(array('name' => $name))) === NULL) {
			$entity = new CurrencyEntity;
			$entity->setName($name);
			$this->currencyRepository->save($entity);
		}

		return $entity;
	}


	/**
	 * @param $code
	 * @param null $name
	 * @return BankEntity
	 */
	private function getBankByCode($code, $name = NULL)
	{
		if (($entity = $this->bankRepository->findOneBy(array('code' => $code))) === NULL) {
			$entity = new BankEntity;
			$entity->setName($name ? $name : $code);
			$entity->setCode($code);
			$this->bankRepository->save($entity);
		}

		return $entity;
	}


	/**
	 * @param $number
	 * @param CurrencyEntity $currency
	 * @param $code
	 * @param null $user
	 * @param null $name
	 * @return AccountEntity
	 */
	private function getAccountByNumber($number, CurrencyEntity $currency, $code, $user = NULL, $name = NULL)
	{
		$entity = $this->accountRepository->createQueryBuilder('a')
			->leftJoin('a.bank', 'b')
			->andWhere('b.code = :code')->setParameter('code', $code)
			->andWhere('a.name = :name')->setParameter('name', $number)
			->getQuery()->getOneOrNullResult();

		if ($entity === NULL) {
			$entity = new AccountEntity;
			$entity->setBank($this->getBankByCode($code, $name));
			$entity->setName($number);
			$entity->setCurrency($currency);
			$this->accountRepository->save($entity);
		}

		if (!$entity->getPerson() && $user) {
			$entity->setPerson($this->getPersonByName($user));
			$this->accountRepository->save($entity);
		}

		return $entity;
	}


	/**
	 * @param $name
	 * @return PersonEntity
	 */
	private function getPersonByName($name)
	{
		if (($entity = $this->personRepository->findOneBy(array('name' => $name))) === NULL) {
			$entity = new PersonEntity;
			$entity->setName($name);
			$this->personRepository->save($entity);
		}

		return $entity;
	}

}
