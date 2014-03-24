<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace PaymentsModule\Admin\Payments;

use DirectoryModule\Admin\Directory\PersonEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DoctrineModule\Entities\NamedEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\PaymentsModule\Admin\Payments\AccountRepository")
 * @ORM\Table(name="payments_account")
 */
class AccountEntity extends NamedEntity
{

	/**
	 * @var PaymentEntity[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="PaymentEntity", mappedBy="account")
	 */
	protected $payments;

	/**
	 * @var BankEntity
	 * @ORM\ManyToOne(targetEntity="BankEntity", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $bank;

	/**
	 * @var CurrencyEntity
	 * @ORM\ManyToOne(targetEntity="CurrencyEntity", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $currency;

	/**
	 * @var PersonEntity
	 * @ORM\ManyToOne(targetEntity="\DirectoryModule\Admin\Directory\PersonEntity", cascade={"persist"})
	 */
	protected $person;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $iban;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $bic;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $syncDate;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $options;


	public function __construct()
	{
		$this->payments = new ArrayCollection;
		$this->syncDate = new \DateTime;
		$this->syncDate->modify('-3 years');
		$this->setOptions(array());
	}


	public function __toString()
	{
		if (!$this->bank) {
			return 'new';
		}

		return parent::__toString() . '/' . $this->bank->getCode();
	}


	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection|\PaymentsModule\Admin\Payments\PaymentEntity[] $payments
	 */
	public function setPayments($payments)
	{
		$this->payments = $payments;
	}


	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\PaymentsModule\Admin\Payments\PaymentEntity[]
	 */
	public function getPayments()
	{
		return $this->payments;
	}


	/**
	 * @param PersonEntity $person
	 */
	public function setPerson(PersonEntity $person)
	{
		$this->person = $person;
	}


	/**
	 * @return PersonEntity
	 */
	public function getPerson()
	{
		return $this->person;
	}


	/**
	 * @param BankEntity $bank
	 */
	public function setBank(BankEntity $bank)
	{
		$this->bank = $bank;
	}


	/**
	 * @return BankEntity
	 */
	public function getBank()
	{
		return $this->bank;
	}


	/**
	 * @param string $bic
	 */
	public function setBic($bic)
	{
		$this->bic = $bic ? $bic : NULL;
	}


	/**
	 * @return string
	 */
	public function getBic()
	{
		return $this->bic;
	}


	/**
	 * @param CurrencyEntity $currency
	 */
	public function setCurrency(CurrencyEntity $currency)
	{
		$this->currency = $currency;
	}


	/**
	 * @return CurrencyEntity
	 */
	public function getCurrency()
	{
		return $this->currency;
	}


	/**
	 * @param \DateTime $syncDate
	 */
	public function setSyncDate(\DateTime $syncDate = NULL)
	{
		$this->syncDate = $syncDate;
	}


	/**
	 * @return \DateTime
	 */
	public function getSyncDate()
	{
		return $this->syncDate;
	}


	/**
	 * @param string $iban
	 */
	public function setIban($iban)
	{
		$this->iban = $iban ? $iban : NULL;
	}


	/**
	 * @return string
	 */
	public function getIban()
	{
		return $this->iban;
	}


	/**
	 * @param string $options
	 */
	public function setOptions($options)
	{
		$this->options = json_encode($options);
	}


	/**
	 * @return string
	 */
	public function getOptions()
	{
		return json_decode($this->options, TRUE);
	}

}
