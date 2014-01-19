<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace MailformModule\Pages\Mailform;

use CmsModule\Content\Entities\ExtendedPageEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CmsModule\Content\Repositories\PageRepository")
 * @ORM\Table(name="mailform_page")
 */
class PageEntity extends ExtendedPageEntity
{

	/**
	 * @var MailformEntity
	 * @ORM\OneToOne(targetEntity="MailformEntity", cascade={"all"})
	 */
	protected $mailform;


	protected function startup()
	{
		parent::startup();

		$this->mailform = new MailformEntity();
	}


	/**
	 * @return MailformEntity
	 */
	public function getMailform()
	{
		return $this->mailform;
	}
}
