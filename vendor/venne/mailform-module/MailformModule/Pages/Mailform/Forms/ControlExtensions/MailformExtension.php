<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace MailformModule\Pages\Mailform\Forms\ControlExtensions;

use Nette\Object;
use Venne\Forms\IControlExtension;
use Venne\Forms\Form;
use MailformModule\Pages\Mailform\InputEntity;

/**
 * @author     Josef Kříž
 */
class MailformExtension extends Object implements IControlExtension
{


	/**
	 * @return array
	 */
	public function getControls(Form $form)
	{
		return array(
			'mailform'
		);
	}


	/**
	 * @param Form $form
	 * @param $name
	 * @return \DoctrineModule\Forms\Containers\EntityContainer
	 */
	public function addMailform($form, $name)
	{
		/** @var $container Form */
		$container = $form->addOne($name);

		$container->setCurrentGroup($form->addGroup('Sender'));
		$container->addCheckbox('sendCopyToSender', 'Send copy to sender')->addCondition($form::EQUAL, true)->toggle('copyHeader')->toggle('copyRecipient');
		$container->setCurrentGroup($group = $form->addGroup());
		$group->setOption('id', 'copyHeader');
		$container->addTextArea('copyHeader', 'Header of copy')->getControlPrototype()->attrs['class'] = 'input-block-level';

		$container->setCurrentGroup($form->addGroup('Recipient'));
		$container->addTags('emails', 'E-mails')->addRule($form::FILLED, 'Please set e-mail.');
		$container->setCurrentGroup($group = $form->addGroup());
		$group->setOption('id', 'copyRecipient');
		$container->addText('recipient', 'Recipient name')->addConditionOn($container['sendCopyToSender'], $form::EQUAL, true)->addRule($form::FILLED, 'Please set recipient name.');
		$container->setCurrentGroup($group = $form->addGroup());
		$container->addText('subject', 'Subject')->addRule($form::FILLED, 'Please set subject.');

		$container->addCheckbox('ownTemplate', 'Advanced options')->addCondition($form::EQUAL, true)->toggle('template');

		$container->setCurrentGroup($group = $form->addGroup());
		$group->setOption('id', 'template');
		$container->addTextarea('template', 'Mail template')->getControlPrototype()->attrs['class'] = 'input-block-level';

		$container->setCurrentGroup($group = $form->addGroup('Inputs'));

		$mainContainer = $container;

		/** @var $items \Nette\Forms\Container */
		$items = $container->addMany('inputs', function (\Nette\Forms\Container $container) use ($group, $form, $mainContainer) {
			$container->setCurrentGroup($group);
			$container->addText('label', 'Label');
			$container->addSelect('type', 'Type', InputEntity::getTypes())
				->addCondition($form::IS_IN, array(
						InputEntity::TYPE_CHECKBOX_LIST,
						InputEntity::TYPE_RADIO_LIST,
						InputEntity::TYPE_SELECT)
				)
				->toggle("frm{$form->getUniqueId()}-{$mainContainer->getName()}-inputs-{$container->getName()}-items-pair")
				->endCondition()
				->addCondition($form::IS_IN, array(
						InputEntity::TYPE_CHECKBOX,
						InputEntity::TYPE_TEXT, InputEntity::TYPE_TEXTAREA,
						InputEntity::TYPE_CHECKBOX_LIST,
						InputEntity::TYPE_RADIO_LIST,
						InputEntity::TYPE_SELECT)
				)
				->toggle("frm{$form->getUniqueId()}-{$mainContainer->getName()}-inputs-{$container->getName()}-required-pair");
			$container->addTags('items', 'Items');
			$container->addCheckbox('required', 'Required');

			$container->addSubmit('remove', 'Remove input')
				->addRemoveOnClick();
		});

		$items->setCurrentGroup($group = $form->addGroup());
		$items->addSubmit('add', 'Add input')
			->setValidationScope(FALSE)
			->addCreateOnClick();
	}
}
