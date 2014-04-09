<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Prispet;

use CmsModule\Content\Presenters\PagePresenter;
use Nette\Http\Request;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class RoutePresenter extends PagePresenter
{

	/** @var FormFactory */
	private $formFactory;

	/** @var Request */
	private $httpRequest;


	/**
	 * @param FormFactory $formFactory
	 * @param Request $httpRequest
	 */
	public function __construct(FormFactory $formFactory, Request $httpRequest)
	{
		$this->formFactory = $formFactory;
		$this->httpRequest = $httpRequest;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->invoke();
		$form->onSuccess[] = $this->formSuccess;
		return $form;
	}


	public function formSuccess(Form $form)
	{
		if ($form->isSubmitted() !== $form->getSaveButton()) {
			return;
		}

		$values = $form->values;

		$data = array(
			'code' => 'K0ntrolaH3slem',
			'jmeno' => $values['person'] == 'fyzicka' ? $values['fyzicka']['name'] : $values['pravnicka']['name'] . ' / ' . $values['pravnicka']['person'],
			'narozeni' => $values['person'] == 'fyzicka' ? $values['fyzicka']['bornDate'] : $values['pravnicka']['IC'],
			'ulice' => $values['street'],
			'obec' => $values['city'],
			'psc' => $values['psc'],
			'email' => $values['email'],
			'castka' => $values['money'] == 'other' ? $values['moneyOther'] : $values['money'],
			'ip' => $this->httpRequest->remoteAddress,
		);

		$params = '';
		foreach ($data as $key => $val) {
			$params .= '&' . $key . '=' . urlencode($val);
		}
		$params = ltrim($params, '&');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://s-dary.zvara.cz/inject.php');
		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($status == 200) {
			$this->flashMessage('Děkujeme, částku prosím uhraďte na náš transparentní účet s variabilním symbolem ' . $result, 'success');
		} elseif ($status == 403) {
			$this->flashMessage('Server nemá povolení k provedení akce. Prosím kontaktujte administrátora. ', 'warning');
		} else {
			$this->flashMessage('Nastala chyba při ukládání, zopakujte prosím akci později', 'warning');
		}

		$this->redirect('this');
	}

}
