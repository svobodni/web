<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Dobrovolnik;

use BlogModule\Pages\Blog\AbstractArticleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\SiteModule\Pages\Dobrovolnik\DobrovolnikRepository")
 * @ORM\Table(name="svobodni_dobrovolnik_dobrovolnik")
 */
class DobrovolnikEntity extends AbstractArticleEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $surname;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $email;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $city;

	/**
	 * @var VillageEntity[]
	 * @ORM\ManyToMany(targetEntity="VillageEntity", inversedBy="dobrovolnici", cascade={"all"})
	 */
	protected $villages;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $phone;

	/**
	 * @var string
	 * @ORM\Column(type="decimal", precision=15, scale=10, nullable=true)
	 */
	protected $latitude;

	/**
	 * @var string
	 * @ORM\Column(type="decimal", precision=15, scale=10, nullable=true)
	 */
	protected $longitude;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $homework = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $contactCampaign = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $advertisingArea = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $distributionOfLeaflets = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $distributionOfPosters = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $ownHelp = '';

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $blog = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $discussion = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $skills = '';

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $adFacade = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $adWindow = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $adShop = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $adCar = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $provideCar = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $providePub = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="boolean")
	 */
	protected $provideAccommodation = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $otherwiseHelp = '';


	protected function startup()
	{
		parent::startup();

		$name = Strings::random(20);

		$this->villages = new ArrayCollection;

		$this->getRoute()
			->setPublished(TRUE)
			->setName($name)
			->setTitle($name)
			->setLocalUrl(Strings::webalize($name));
	}


	/**
	 * @param \SiteModule\Pages\Dobrovolnik\VillageEntity[] $villages
	 */
	public function setVillages($villages)
	{
		$this->villages = $villages;
	}


	/**
	 * @return \SiteModule\Pages\Dobrovolnik\VillageEntity[]
	 */
	public function getVillages()
	{
		return $this->villages;
	}


	/**
	 * @param string $distributionOfPosters
	 */
	public function setDistributionOfPosters($distributionOfPosters)
	{
		$this->distributionOfPosters = $distributionOfPosters;
	}


	/**
	 * @return string
	 */
	public function getDistributionOfPosters()
	{
		return $this->distributionOfPosters;
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
	 * @param string $adCar
	 */
	public function setAdCar($adCar)
	{
		$this->adCar = $adCar;
	}


	/**
	 * @return string
	 */
	public function getAdCar()
	{
		return $this->adCar;
	}


	/**
	 * @param string $adFacade
	 */
	public function setAdFacade($adFacade)
	{
		$this->adFacade = $adFacade;
	}


	/**
	 * @return string
	 */
	public function getAdFacade()
	{
		return $this->adFacade;
	}


	/**
	 * @param string $adShop
	 */
	public function setAdShop($adShop)
	{
		$this->adShop = $adShop;
	}


	/**
	 * @return string
	 */
	public function getAdShop()
	{
		return $this->adShop;
	}


	/**
	 * @param string $adWindow
	 */
	public function setAdWindow($adWindow)
	{
		$this->adWindow = $adWindow;
	}


	/**
	 * @return string
	 */
	public function getAdWindow()
	{
		return $this->adWindow;
	}


	/**
	 * @param string $advertisingArea
	 */
	public function setAdvertisingArea($advertisingArea)
	{
		$this->advertisingArea = $advertisingArea;
	}


	/**
	 * @return string
	 */
	public function getAdvertisingArea()
	{
		return $this->advertisingArea;
	}


	/**
	 * @param string $blog
	 */
	public function setBlog($blog)
	{
		$this->blog = $blog;
	}


	/**
	 * @return string
	 */
	public function getBlog()
	{
		return $this->blog;
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
	 * @param string $contactCampaign
	 */
	public function setContactCampaign($contactCampaign)
	{
		$this->contactCampaign = $contactCampaign;
	}


	/**
	 * @return string
	 */
	public function getContactCampaign()
	{
		return $this->contactCampaign;
	}


	/**
	 * @param string $discussion
	 */
	public function setDiscussion($discussion)
	{
		$this->discussion = $discussion;
	}


	/**
	 * @return string
	 */
	public function getDiscussion()
	{
		return $this->discussion;
	}


	/**
	 * @param string $distributionOfLeaflets
	 */
	public function setDistributionOfLeaflets($distributionOfLeaflets)
	{
		$this->distributionOfLeaflets = $distributionOfLeaflets;
	}


	/**
	 * @return string
	 */
	public function getDistributionOfLeaflets()
	{
		return $this->distributionOfLeaflets;
	}


	/**
	 * @param string $homework
	 */
	public function setHomework($homework)
	{
		$this->homework = $homework;
	}


	/**
	 * @return string
	 */
	public function getHomework()
	{
		return $this->homework;
	}


	/**
	 * @param string $latitude
	 */
	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;
	}


	/**
	 * @return string
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}


	/**
	 * @param string $longitude
	 */
	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;
	}


	/**
	 * @return string
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}


	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $otherwiseHelp
	 */
	public function setOtherwiseHelp($otherwiseHelp)
	{
		$this->otherwiseHelp = $otherwiseHelp;
	}


	/**
	 * @return string
	 */
	public function getOtherwiseHelp()
	{
		return $this->otherwiseHelp;
	}


	/**
	 * @param string $ownHelp
	 */
	public function setOwnHelp($ownHelp)
	{
		$this->ownHelp = $ownHelp;
	}


	/**
	 * @return string
	 */
	public function getOwnHelp()
	{
		return $this->ownHelp;
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
	 * @param string $provideAccommodation
	 */
	public function setProvideAccommodation($provideAccommodation)
	{
		$this->provideAccommodation = $provideAccommodation;
	}


	/**
	 * @return string
	 */
	public function getProvideAccommodation()
	{
		return $this->provideAccommodation;
	}


	/**
	 * @param string $provideCar
	 */
	public function setProvideCar($provideCar)
	{
		$this->provideCar = $provideCar;
	}


	/**
	 * @return string
	 */
	public function getProvideCar()
	{
		return $this->provideCar;
	}


	/**
	 * @param string $providePub
	 */
	public function setProvidePub($providePub)
	{
		$this->providePub = $providePub;
	}


	/**
	 * @return string
	 */
	public function getProvidePub()
	{
		return $this->providePub;
	}


	/**
	 * @param string $skills
	 */
	public function setSkills($skills)
	{
		$this->skills = $skills;
	}


	/**
	 * @return string
	 */
	public function getSkills()
	{
		return $this->skills;
	}


	/**
	 * @param string $surname
	 */
	public function setSurname($surname)
	{
		$this->surname = $surname;
	}


	/**
	 * @return string
	 */
	public function getSurname()
	{
		return $this->surname;
	}

}
