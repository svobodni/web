<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Kandidat;

use CmsModule\Content\Entities\ExtendedPageEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CmsModule\Content\Repositories\PageRepository")
 * @ORM\Table(name="svobodni_kandidat_page")
 */
class PageEntity extends ExtendedPageEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $position = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $email = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $city = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $street = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $zip = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $phone = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $fb = '';

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $account;


	/**
	 * @param string $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}


	/**
	 * @return string
	 */
	public function getPosition()
	{
		return $this->position;
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
	 * @param string $fb
	 */
	public function setFb($fb)
	{
		$this->fb = $fb;
	}


	/**
	 * @return string
	 */
	public function getFb()
	{
		return $this->fb;
	}


	/**
	 * @param float $gpsLat
	 */
	public function setGpsLat($gpsLat)
	{
		$this->gpsLat = $gpsLat;
	}


	/**
	 * @return float
	 */
	public function getGpsLat()
	{
		return $this->gpsLat;
	}


	/**
	 * @param float $gpsLong
	 */
	public function setGpsLong($gpsLong)
	{
		$this->gpsLong = $gpsLong;
	}


	/**
	 * @return float
	 */
	public function getGpsLong()
	{
		return $this->gpsLong;
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
	 * @param string $account
	 */
	public function setAccount($account)
	{
		$this->account = $account ? : NULL;
	}


	/**
	 * @return string
	 */
	public function getAccount()
	{
		return $this->account;
	}

}
