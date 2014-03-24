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

use Doctrine\ORM\Mapping as ORM;
use DoctrineModule\Entities\IdentifiedEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\PaymentsModule\Admin\Payments\PaymentRepository")
 * @ORM\Table(name="payments_payment")
 */
class PaymentEntity extends IdentifiedEntity
{

	/**
	 * @var PaymentEntity
	 * @ORM\ManyToOne(targetEntity="AccountEntity", inversedBy="payments")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $account;


	/**
	 * @var AccountEntity
	 * @ORM\ManyToOne(targetEntity="AccountEntity", cascade={"persist"})
	 */
	protected $offset;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $paymentId;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $date;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="decimal", precision=20, scale=5)
	 */
	protected $amount = 0.0;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $constantSymbol;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $variableSymbol;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $specificSymbol;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $userIdentification = '';

	/**
	 * @var string
	 * @ORM\Column(type="string", length=140)
	 */
	protected $message = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $type = '';

	/**
	 * @var string
	 * @ORM\Column(type="string", length=50)
	 */
	protected $performed = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $specification = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $comment = '';

	/**
	 * @var string
	 * @ORM\Column(type="string", length=11)
	 */
	protected $bic = '';

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $instructionId;


	public function __toString()
	{
		return (string)$this->paymentId . ' (' . $this->date->format('Y-m-d H:i:s') . ')';
	}


	/**
	 * @param AccountEntity $account
	 */
	public function __construct(AccountEntity $account = NULL)
	{
		$this->account = $account;
		$this->date = new \DateTime;
	}


	/**
	 * @param AccountEntity $account
	 */
	public function setAccount(AccountEntity $account)
	{
		$this->account = $account;
	}


	/**
	 * @return PaymentEntity
	 */
	public function getAccount()
	{
		return $this->account;
	}


	/**
	 * @param \DateTime $amount
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;
	}


	/**
	 * @return \DateTime
	 */
	public function getAmount()
	{
		return $this->amount;
	}


	/**
	 * @param string $bic
	 */
	public function setBic($bic)
	{
		$this->bic = $bic;
	}


	/**
	 * @return string
	 */
	public function getBic()
	{
		return $this->bic;
	}


	/**
	 * @param string $comment
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;
	}


	/**
	 * @return string
	 */
	public function getComment()
	{
		return $this->comment;
	}


	/**
	 * @param int $constantSymbol
	 */
	public function setConstantSymbol($constantSymbol)
	{
		$this->constantSymbol = $constantSymbol;
	}


	/**
	 * @return int
	 */
	public function getConstantSymbol()
	{
		return $this->constantSymbol;
	}


	/**
	 * @param \DateTime $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
	}


	/**
	 * @return \DateTime
	 */
	public function getDate()
	{
		return $this->date;
	}


	/**
	 * @param int $instructionId
	 */
	public function setInstructionId($instructionId)
	{
		$this->instructionId = $instructionId;
	}


	/**
	 * @return int
	 */
	public function getInstructionId()
	{
		return $this->instructionId;
	}


	/**
	 * @param string $message
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}


	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}


	/**
	 * @param AccountEntity $offset
	 */
	public function setOffset(AccountEntity $offset)
	{
		$this->offset = $offset;
	}


	/**
	 * @return AccountEntity
	 */
	public function getOffset()
	{
		return $this->offset;
	}


	/**
	 * @param int $paymentId
	 */
	public function setPaymentId($paymentId)
	{
		$this->paymentId = $paymentId;
	}


	/**
	 * @return int
	 */
	public function getPaymentId()
	{
		return $this->paymentId;
	}


	/**
	 * @param string $performed
	 */
	public function setPerformed($performed)
	{
		$this->performed = $performed;
	}


	/**
	 * @return string
	 */
	public function getPerformed()
	{
		return $this->performed;
	}


	/**
	 * @param int $specificSymbol
	 */
	public function setSpecificSymbol($specificSymbol)
	{
		$this->specificSymbol = $specificSymbol;
	}


	/**
	 * @return int
	 */
	public function getSpecificSymbol()
	{
		return $this->specificSymbol;
	}


	/**
	 * @param string $specification
	 */
	public function setSpecification($specification)
	{
		$this->specification = $specification;
	}


	/**
	 * @return string
	 */
	public function getSpecification()
	{
		return $this->specification;
	}


	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @param string $userIdentification
	 */
	public function setUserIdentification($userIdentification)
	{
		$this->userIdentification = $userIdentification;
	}


	/**
	 * @return string
	 */
	public function getUserIdentification()
	{
		return $this->userIdentification;
	}


	/**
	 * @param int $variableSymbol
	 */
	public function setVariableSymbol($variableSymbol)
	{
		$this->variableSymbol = $variableSymbol;
	}


	/**
	 * @return int
	 */
	public function getVariableSymbol()
	{
		return $this->variableSymbol;
	}

}
