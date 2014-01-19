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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DoctrineModule\Entities\IdentifiedEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\CmsModule\Content\Repositories\PageRepository")
 * @ORM\Table(name="mailform_mailform")
 */
class MailformEntity extends IdentifiedEntity
{

	/**
	 * @var ArrayCollection|InputEntity[]
	 * @ORM\OneToMany(targetEntity="InputEntity", mappedBy="parent", cascade={"persist"})
	 */
	protected $inputs;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $emails;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $recipient = '';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $subject;


	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $template = 'Name: {$name}
E-mail: {$email}

{foreach $inputs as $input}
{if $input[\'entity\']->getType() === \MailformModule\Pages\Mailform\InputEntity::TYPE_GROUP}

{=$input[\'entity\']->getLabel()}
{=str_repeat(\'-\', strlen($input[\'entity\']->getLabel()))}

{else}

{=$input[\'entity\']->getLabel()}: {$input[\'value\']}

{/if}
{/foreach}
';

	/**
	 * @var bool
	 * @ORM\Column(type="boolean")
	 */
	protected $sendCopyToSender = true;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $copyHeader = 'Original message:

';


	public function __construct()
	{
		parent::__construct();

		$this->emails = '';
		$this->subject = '';

		$this->inputs = new ArrayCollection;
		$this->inputs[] = new InputEntity($this, InputEntity::TYPE_TEXTAREA, 'Text');
	}


	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $inputs
	 */
	public function setInputs($inputs)
	{
		$this->inputs = $inputs;
	}


	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getInputs()
	{
		return $this->inputs;
	}


	/**
	 * @param string $emails
	 */
	public function setEmails($emails)
	{
		$this->emails = implode(';', $emails);
	}


	/**
	 * @return string
	 */
	public function getEmails()
	{
		return explode(';', $this->emails);
	}


	/**
	 * @param string $recipient
	 */
	public function setRecipient($recipient)
	{
		$this->recipient = $recipient;
	}


	/**
	 * @return string
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}


	/**
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}


	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}


	/**
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}


	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}


	/**
	 * @param boolean $sendCopyToSender
	 */
	public function setSendCopyToSender($sendCopyToSender)
	{
		$this->sendCopyToSender = $sendCopyToSender;
	}


	/**
	 * @return boolean
	 */
	public function getSendCopyToSender()
	{
		return $this->sendCopyToSender;
	}


	/**
	 * @param string $copyHeader
	 */
	public function setCopyHeader($copyHeader)
	{
		$this->copyHeader = $copyHeader;
	}


	/**
	 * @return string
	 */
	public function getCopyHeader()
	{
		return $this->copyHeader;
	}
}
