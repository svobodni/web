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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DoctrineModule\Entities\NamedEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\SiteModule\Pages\Dobrovolnik\VillageRepository")
 * @ORM\Table(name="svobodni_dobrovolnik_village")
 */
class VillageEntity extends NamedEntity
{

	/**
	 * @var DobrovolnikEntity[]
	 * @ORM\ManyToMany(targetEntity="DobrovolnikEntity", mappedBy="villages")
	 **/
	protected $dobrovolnici;

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


	public function __construct()
	{
		$this->dobrovolnici = new ArrayCollection;
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
	 * @param \SiteModule\Pages\Dobrovolnik\DobrovolnikEntity[] $dobrovolnici
	 */
	public function setDobrovolnici($dobrovolnici)
	{
		$this->dobrovolnici = $dobrovolnici;
	}


	/**
	 * @return \SiteModule\Pages\Dobrovolnik\DobrovolnikEntity[]
	 */
	public function getDobrovolnici()
	{
		return $this->dobrovolnici;
	}




}
