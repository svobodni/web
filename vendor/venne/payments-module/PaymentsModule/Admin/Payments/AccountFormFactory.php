<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace PaymentsModule\Admin\Payments;

use DoctrineModule\Forms\FormFactory;
use FormsModule\ControlExtensions\ControlExtension;
use PaymentsModule\PaymentManager;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AccountFormFactory extends FormFactory
{

	/** @var PaymentManager */
	private $paymentManager;

	/** @var BankRepository */
	private $bankRepository;

	/**
	 * @param PaymentManager $paymentManager
	 */
	public function inject(PaymentManager $paymentManager, BankRepository $bankRepository)
	{
		$this->paymentManager = $paymentManager;
		$this->bankRepository = $bankRepository;
	}


	protected function getControlExtensions()
	{
		return array_merge(parent::getControlExtensions(), array(
			new ControlExtension,
			new \CmsModule\Content\ControlExtension,
		));
	}


	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$codes = $this->getCodes();

		$form->addGroup();
		$form->addManyToOne('person', 'Person');
		$c = $form->addManyToOne('bank', 'Bank')
			->addRule($form::FILLED);

		foreach ($codes as $id => $code) {
			$c->addCondition($form::EQUAL, $id)->toggle('form-' . $code);
		}

		$form->addDateTime('syncDate', 'Last sync. date')
			->addRule($form::FILLED);

		$form->addText('name', 'Account number')
			->addRule($form::FILLED);

		$form->addManyToOne('currency', 'Currency')
			->addRule($form::FILLED);

		$form->addText('iban', 'IBAN');
		$form->addText('bic', 'BIC');

		$drivers = $form->addContainer('drivers');
		foreach ($this->paymentManager->getDrivers() as $driver) {
			$container = $drivers->addContainer($driver->getCode());
			$container->setCurrentGroup($form->addGroup($driver->getName())->setOption('id', 'form-' . $driver->getCode()));
			$driver->configureOptionsContainer($container);
		}

		$form->addGroup();
		$form->addSaveButton('Save');
	}


	public function handleSave(Form $form)
	{
		$bank = $form['bank']->value;
		if ($bank && isset($form['drivers'][$bank->code])) {
			$form->data->options = $form['drivers'][$bank->code]->values;
		}

		parent::handleSave($form);
	}


	public function handleLoad(Form $form)
	{
		$bank = $form->data->bank;
		if ($bank && isset($form['drivers'][$bank->code])) {
			$form['drivers'][$bank->code]->values = $form->data->options;
		}
	}


	/**
	 * @return array
	 */
	private function getCodes()
	{
		$ret = array();

		foreach ($this->paymentManager->getDrivers() as $driver) {
			$bank = $this->bankRepository->findOneBy(array('code' => $driver->getCode()));
			$ret[$bank->id] = $driver->getCode();
		}

		return $ret;
	}

}
