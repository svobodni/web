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

use CmsModule\Content\Control;
use Nette\Templating\Template;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class MailControl extends Control
{

	/** @var array */
	public $onSuccess;

	/** @var array */
	public $onSendMessage;

	/** @var array */
	public $onSendCopyMessage;

	/** @var MailformfrontFormFactory */
	protected $formFactory;

	/** @var MailformEntity */
	protected $mailformEntity;


	/**
	 * @param MailformEntity $mailformEntity
	 * @param MailformfrontFormFactory $formFactory
	 */
	public function __construct(MailformEntity $mailformEntity, MailformfrontFormFactory $formFactory)
	{
		parent::__construct();

		$this->mailformEntity = $mailformEntity;
		$this->formFactory = $formFactory;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->invoke($this->mailformEntity);
		$form->onSuccess[] = $this->formSuccess;
		return $form;
	}


	public function formSuccess(Form $form)
	{
		$mail = $this->getMail();
		$this->onSendMessage($this, $mail);
		$mail->send();

		if ($this->mailformEntity->getSendCopyToSender()) {
			$mail = $this->getCopyMail();
			$this->onSendCopyMessage($this, $mail);
			$mail->send();
		}

		$this->onSuccess($this);
	}


	/**
	 * @return string
	 */
	protected function getMessage()
	{
		/** @var $template Template */
		$template = $this->createTemplate('Nette\Templating\Template');
		$template->setSource($this->mailformEntity->getTemplate());
		$values = $this['form']['_inputs']->getValues();

		$template->name = $values['_name'];
		$template->email = $values['_email'];

		$template->inputs = array();
		foreach ($this->mailformEntity->inputs as $key => $input) {
			$val = $values['input_' . $key];
			if (is_array($val)) {
				$val = implode(' ; ', $val);
			}

			$template->inputs[] = array('entity' => $input, 'value' => $val);
		}

		return $template->__toString();
	}


	/**
	 * @return string
	 */
	protected function getCopyMessage()
	{
		/** @var $template Template */
		$template = $this->createTemplate('Nette\Templating\Template');
		$template->setSource($this->mailformEntity->getCopyHeader());

		return $template->__toString() . $this->getMessage();
	}


	/**
	 * @param null|string $message
	 * @return \Nette\Mail\Message
	 */
	protected function getMail($message = NULL)
	{
		$values = $this['form']['_inputs']->getValues();

		$mail = new \Nette\Mail\Message();
		$mail->setFrom("{$values['_name']} <{$values['_email']}>");

		foreach ($this->mailformEntity->emails as $email) {
			$mail->addTo($email);
		}

		$mail->setSubject($this->mailformEntity->subject);
		$mail->setBody($message ? $message : $this->getMessage());
		return $mail;
	}


	/**
	 * @param null|string $message
	 * @return \Nette\Mail\Message
	 */
	protected function getCopyMail($message = NULL)
	{
		$values = $this['form']['_inputs']->getValues();

		$mail = new \Nette\Mail\Message();
		$mail->setFrom($this->mailformEntity->emails[0], $this->mailformEntity->recipient);
		$mail->addTo($values['_email'], $values['_name']);

		$mail->setSubject($this->mailformEntity->subject);
		$mail->setBody($message ? $message : $this->getCopyMessage());
		return $mail;
	}


	public function render()
	{
		$this['form']->render();
	}
}
