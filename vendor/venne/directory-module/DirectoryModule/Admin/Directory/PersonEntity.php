<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DirectoryModule\Admin\Directory;

use CmsModule\Content\Entities\DirEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DoctrineModule\Entities\NamedEntity;
use Nette\Utils\Strings;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\DirectoryModule\Admin\Directory\PersonRepository")
 * @ORM\Table(name="directory_person")
 */
class PersonEntity extends NamedEntity
{

	const TYPE_ARTIFICIAL = 'artificial person';

	const TYPE_NATURAL = 'natural person';

	/** @var array */
	protected static $types = array(
		self::TYPE_NATURAL => self::TYPE_NATURAL,
		self::TYPE_ARTIFICIAL => self::TYPE_ARTIFICIAL,
	);

	/**
	 * @var string
	 * @ORM\Column(type="string", length=30)
	 */
	protected $type = self::TYPE_NATURAL;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $description = '';

	/**
	 * @var \CmsModule\Pages\Users\UserEntity[]
	 * @ORM\ManyToMany(targetEntity="\CmsModule\Pages\Users\UserEntity")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $users;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $street = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $number = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $city = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $zip = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $identificationNumber = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $taxIdentificationNumber = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $registration = '';

	/**
	 * @var bool
	 * @ORM\Column(type="boolean")
	 */
	protected $taxpayer = TRUE;

	/**
	 * @var DirEntity::
	 * @ORM\OneToOne(targetEntity="\CmsModule\Content\Entities\DirEntity", cascade={"all"})
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $dir;

	/**
	 * @var FileEntity
	 * @ORM\OneToOne(targetEntity="\CmsModule\Content\Entities\FileEntity", cascade={"all"}, orphanRemoval=true)
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $logo;

	/**
	 * @var FileEntity
	 * @ORM\OneToOne(targetEntity="\CmsModule\Content\Entities\FileEntity", cascade={"all"}, orphanRemoval=true)
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $signature;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $email = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $phone = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $website = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $fax = '';


	public function __construct()
	{
		$this->users = new ArrayCollection;

		$this->dir = new DirEntity;
		$this->dir->setInvisible(TRUE);
		$this->dir->setName(Strings::webalize(get_class($this)) . Strings::random());
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
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}


	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}


	/**
	 * @param string $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}


	/**
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}


	/**
	 * @param string $identificationNumber
	 */
	public function setIdentificationNumber($identificationNumber)
	{
		$this->identificationNumber = $identificationNumber;
	}


	/**
	 * @return string
	 */
	public function getIdentificationNumber()
	{
		return $this->identificationNumber;
	}


	/**
	 * @param string $taxIdentificationNumber
	 */
	public function setTaxIdentificationNumber($taxIdentificationNumber)
	{
		$this->taxIdentificationNumber = $taxIdentificationNumber;
	}


	/**
	 * @return string
	 */
	public function getTaxIdentificationNumber()
	{
		return $this->taxIdentificationNumber;
	}


	/**
	 * @param string $registration
	 */
	public function setRegistration($registration)
	{
		$this->registration = $registration;
	}


	/**
	 * @return string
	 */
	public function getRegistration()
	{
		return $this->registration;
	}


	/**
	 * @param string $number
	 */
	public function setNumber($number)
	{
		$this->number = $number;
	}


	/**
	 * @return string
	 */
	public function getNumber()
	{
		return $this->number;
	}


	/**
	 * @param string $street
	 */
	public function setStreet($street)
	{
		$this->street = $street;
	}


	/**
	 * @return string
	 */
	public function getStreet()
	{
		return $this->street;
	}


	/**
	 * @param boolean $taxpayer
	 */
	public function setTaxpayer($taxpayer)
	{
		$this->taxpayer = $taxpayer;
	}


	/**
	 * @return boolean
	 */
	public function getTaxpayer()
	{
		return $this->taxpayer;
	}


	/**
	 * @param \CmsModule\Pages\Users\UserEntity[] $users
	 */
	public function setUsers($users)
	{
		$this->users = $users;
	}


	/**
	 * @return \CmsModule\Pages\Users\UserEntity[]
	 */
	public function getUsers()
	{
		return $this->users;
	}


	/**
	 * @param string $zip
	 */
	public function setZip($zip)
	{
		$this->zip = $zip;
	}


	/**
	 * @return string
	 */
	public function getZip()
	{
		return $this->zip;
	}


	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}


	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}


	/**
	 * @param string $fax
	 */
	public function setFax($fax)
	{
		$this->fax = $fax;
	}


	/**
	 * @return string
	 */
	public function getFax()
	{
		return $this->fax;
	}


	/**
	 * @param string $phone
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}


	/**
	 * @return string
	 */
	public function getPhone()
	{
		return $this->phone;
	}


	/**
	 * @param string $website
	 */
	public function setWebsite($website)
	{
		$this->website = $website;
	}


	/**
	 * @return string
	 */
	public function getWebsite()
	{
		return $this->website;
	}


	/**
	 * @param \PaymentsModule\Admin\Payments\FileEntity $logo
	 */
	public function setLogo($logo)
	{
		$this->logo = $logo;

		if ($this->logo) {
			$this->logo->setParent($this->dir);
			$this->logo->setInvisible(TRUE);
		}
	}


	/**
	 * @return \PaymentsModule\Admin\Payments\FileEntity
	 */
	public function getLogo()
	{
		return $this->logo;
	}


	/**
	 * @param \PaymentsModule\Admin\Payments\FileEntity $signature
	 */
	public function setSignature($signature)
	{
		$this->signature = $signature;

		if ($this->signature) {
			$this->signature->setParent($this->dir);
			$this->signature->setInvisible(TRUE);
		}
	}


	/**
	 * @return \PaymentsModule\Admin\Payments\FileEntity
	 */
	public function getSignature()
	{
		return $this->signature;
	}


	/**
	 * @return array
	 */
	public static function getTypes()
	{
		return self::$types;
	}

}
