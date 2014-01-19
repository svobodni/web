<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace EventsModule\Pages\Events;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AbstractArticleEntity extends \BlogModule\Pages\Blog\AbstractArticleEntity
{

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $date;


	protected function startup()
	{
		parent::startup();

		$this->date = new \DateTime;
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

}
