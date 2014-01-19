<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Security;

use CmsModule\Pages\Users\ExtendedUserEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\SiteModule\Security\UserRepository")
 * @ORM\Table(name="svobodni_users")
 */
class UserEntity extends ExtendedUserEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $address;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $birthDate;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $phone;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $website;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $facebook;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $twitter;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $linkedIn;


	/**
	 * @param string $address
	 */
	public function setAddress($address)
	{
		$this->address = $address ? : NULL;
	}


	/**
	 * @return string
	 */
	public function getAddress()
	{
		return $this->address;
	}


	/**
	 * @param \DateTime $birthDate
	 */
	public function setBirthDate($birthDate)
	{
		$this->birthDate = $birthDate ? : NULL;
	}


	/**
	 * @return \DateTime
	 */
	public function getBirthDate()
	{
		return $this->birthDate;
	}


	/**
	 * @param string $facebook
	 */
	public function setFacebook($facebook)
	{
		$this->facebook = $facebook ? : $facebook;
	}


	/**
	 * @return string
	 */
	public function getFacebook()
	{
		return $this->facebook;
	}


	/**
	 * @param string $linkedIn
	 */
	public function setLinkedIn($linkedIn)
	{
		$this->linkedIn = $linkedIn ? : NULL;
	}


	/**
	 * @return string
	 */
	public function getLinkedIn()
	{
		return $this->linkedIn;
	}


	/**
	 * @param string $phone
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone ? : $phone;
	}


	/**
	 * @return string
	 */
	public function getPhone()
	{
		return $this->phone;
	}


	/**
	 * @param string $twitter
	 */
	public function setTwitter($twitter)
	{
		$this->twitter = $twitter ? : $twitter;
	}


	/**
	 * @return string
	 */
	public function getTwitter()
	{
		return $this->twitter;
	}


	/**
	 * @param string $website
	 */
	public function setWebsite($website)
	{
		$this->website = $website ? : $website;
	}


	/**
	 * @return string
	 */
	public function getWebsite()
	{
		return $this->website;
	}

}
