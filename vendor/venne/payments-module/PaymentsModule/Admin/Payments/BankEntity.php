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
use DoctrineModule\Entities\NamedEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\PaymentsModule\Admin\Payments\BankRepository")
 * @ORM\Table(name="payments_bank")
 */
class BankEntity extends NamedEntity
{

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $code;


	/**
	 * @param int $code
	 */
	public function setCode($code)
	{
		$this->code = (int)$code;
	}


	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

}
