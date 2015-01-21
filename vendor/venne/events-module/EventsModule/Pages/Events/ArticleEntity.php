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
 * @ORM\Entity(repositoryClass="\EventsModule\Pages\Events\RouteRepository")
 * @ORM\Table(name="events_article_route")
 */
class ArticleEntity extends AbstractArticleEntity
{

	/**
	 * @var string|null
	 *
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $facebookLink;

	/**
	 * @return null|string
	 */
	public function getFacebookLink()
	{
		return $this->facebookLink;
	}

	/**
	 * @param null|string $facebookLink
	 */
	public function setFacebookLink($facebookLink)
	{
		$this->facebookLink = $facebookLink ? $facebookLink : null;
	}

}
