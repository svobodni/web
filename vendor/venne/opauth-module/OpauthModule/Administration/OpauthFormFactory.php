<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace OpauthModule\Administration;

use FormsModule\Mappers\ConfigMapper;
use Venne\Forms\Form;
use Venne\Forms\FormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class OpauthFormFactory extends FormFactory
{

	/** @var ConfigMapper */
	protected $mapper;


	/**
	 * @param ConfigMapper $mapper
	 */
	public function __construct(ConfigMapper $mapper)
	{
		$this->mapper = $mapper;
	}


	protected function getMapper()
	{
		$mapper = clone $this->mapper;
		$mapper->setRoot('opauth.providers');
		return $mapper;
	}


	/**
	 * @param Form $form
	 */
	protected function configure(Form $form)
	{
		// Facebook
		$provider =$form->addContainer('facebook');
		$provider->setCurrentGroup($form->addGroup('Facebook'));
		$e = $provider->addCheckbox('enabled', 'Enable');
		$e->addCondition($form::EQUAL, TRUE)->toggle('form-facebook');

		$provider->setCurrentGroup($form->addGroup()->setOption('id', 'form-facebook'));
		$provider->addText('appId', 'App ID')->addConditionOn($e, $form::EQUAL, TRUE)->addRule($form::FILLED);
		$provider->addText('secret', 'Secret')->addConditionOn($e, $form::EQUAL, TRUE)->addRule($form::FILLED);
		$provider->addText('scope', 'Scope');


//		// Google
//		$provider =$form->addContainer('google');
//		$provider->setCurrentGroup($form->addGroup('Google'));
//		$e = $provider->addCheckbox('enabled', 'Enable');
//		$e->addCondition($form::EQUAL, TRUE)->toggle('form-google');
//
//		$provider->setCurrentGroup($form->addGroup()->setOption('id', 'form-google'));
//		$provider->addText('appId', 'App ID')->addConditionOn($e, $form::EQUAL, TRUE)->addRule($form::FILLED);
//		$provider->addText('secret', 'Secret')->addConditionOn($e, $form::EQUAL, TRUE)->addRule($form::FILLED);
//		$provider->addText('scope', 'Scope');


//		// Twitter
//		$provider =$form->addContainer('twitter');
//		$provider->setCurrentGroup($form->addGroup('Twitter'));
//		$e = $provider->addCheckbox('enabled', 'Enable');
//		$e->addCondition($form::EQUAL, TRUE)->toggle('form-twitter');
//
//		$provider->setCurrentGroup($form->addGroup()->setOption('id', 'form-twitter'));
//		$provider->addText('key', 'Key')->addConditionOn($e, $form::EQUAL, TRUE)->addRule($form::FILLED);
//		$provider->addText('secret', 'Secret')->addConditionOn($e, $form::EQUAL, TRUE)->addRule($form::FILLED);


		// OpenID
		$provider =$form->addContainer('openid');
		$provider->setCurrentGroup($form->addGroup('OpenID'));
		$provider->addCheckbox('enabled', 'Enable');


		// Private OpenID
		$provider =$form->addContainer('privateOpenid');
		$provider->setCurrentGroup($form->addGroup('Private OpenID'));
		$e = $provider->addCheckbox('enabled', 'Enable');
		$e->addCondition($form::EQUAL, TRUE)->toggle('form-privateOpenid');

		$provider->setCurrentGroup($form->addGroup()->setOption('id', 'form-privateOpenid'));
		$provider->addText('server', 'Server')->addConditionOn($e, $form::EQUAL, TRUE)
			->addRule($form::FILLED)
			->addRule($form::URL);


		// Github
		$provider =$form->addContainer('github');
		$provider->setCurrentGroup($form->addGroup('Github'));
		$e = $provider->addCheckbox('enabled', 'Enable');
		$e->addCondition($form::EQUAL, TRUE)->toggle('form-github');

		$provider->setCurrentGroup($form->addGroup()->setOption('id', 'form-github'));
		$provider->addText('appId', 'App ID')->addConditionOn($e, $form::EQUAL, TRUE)->addRule($form::FILLED);
		$provider->addText('secret', 'Secret')->addConditionOn($e, $form::EQUAL, TRUE)->addRule($form::FILLED);
		$provider->addText('scope', 'Scope');


		$form->addGroup();
		$form->addSubmit('_submit', 'Save');
	}

}
