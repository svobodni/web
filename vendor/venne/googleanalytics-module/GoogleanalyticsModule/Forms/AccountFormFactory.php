<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule\Forms;

use Nette\Utils\Html;
use Venne;
use Venne\Forms\Form;
use Venne\Forms\FormFactory;
use FormsModule\Mappers\ConfigMapper;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AccountFormFactory extends FormFactory
{

	/** @var string */
	protected $googleanalyticsDir;

	/** @var ConfigMapper */
	protected $mapper;


	/**
	 * @param ConfigMapper $mapper
	 */
	public function __construct(ConfigMapper $mapper, $googleanalyticsDir)
	{
		$this->mapper = $mapper;
		$this->googleanalyticsDir = $googleanalyticsDir;
	}


	protected function getMapper()
	{
		$mapper = clone $this->mapper;
		$mapper->setRoot('googleanalytics');
		return $mapper;
	}


	/**
	 * @param Form $form
	 */
	protected function configure(Form $form)
	{
		$account = $form->addContainer('account');
		$account->setCurrentGroup($form->addGroup("Account"));
		$account->addCheckbox('activated', 'Activate')->addCondition($form::EQUAL, TRUE)->toggle('form-account-accountId');

		$account->setCurrentGroup($form->addGroup()->setOption('id', 'form-account-accountId'));
		$account->addText('accountId', 'Account ID');

		$api = $form->addContainer('api');
		$api->setCurrentGroup($form->addGroup('Access by API'));
		$api->addCheckbox('activated', 'Activate')->addCondition($form::EQUAL, TRUE)->toggle('form-api');

		$api->setCurrentGroup($form->addGroup()->setOption('id', 'form-api'));
		$api->addText('applicationName', 'Application name');
		$api->addText('clientId', 'Client ID')->getControlPrototype()->class[] = 'span12';
		$api->addText('clientMail', 'Client mail')->getControlPrototype()->class[] = 'span12';

		$gaId = $api->addText('gaId', 'GA ID or URL');
		$gaId->getControlPrototype()->class[] = 'span12';
		$gaId->getControlPrototype()->onChange[] = 'if ($(this).val().length > 20) { var url = $(this).val().split("p"); url = url[url.length - 1]; if(url.substr(url.length -1 , 1) == "/") { url = url.substr(0, url.length - 1); } $(this).val(url); }';

		if(file_exists($this->googleanalyticsDir . '/key.p12')) {
			$api->addCheckbox('keyFileNew', 'Change private key')->addCondition($form::EQUAL, TRUE)->toggle('group-keyFile');
		}

		$api->setCurrentGroup($form->addGroup()->setOption('id', 'group-keyFile'));
		$api->addUpload('keyFile', 'Private key');

		$form->addGroup();
		$form->addSaveButton('Save');
	}


	public function handleSave(Form $form)
	{
		$values = $form->getValues();

		if ($values['api']['keyFile']->isOk()) {
			$values['api']['keyFile']->move($this->googleanalyticsDir . '/key.p12');
		}

		unset($form['api']['keyFile']);
		unset($form['api']['keyFileNew']);
	}
}
