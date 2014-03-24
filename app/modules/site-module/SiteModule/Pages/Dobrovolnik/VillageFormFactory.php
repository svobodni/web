<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace SiteModule\Pages\Dobrovolnik;

use DoctrineModule\Forms\FormFactory;
use Nette\Forms\Controls\Button;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class VillageFormFactory extends FormFactory
{


	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addText('name', 'Jméno')
			->addRule($form::FILLED, 'Vyplňte prosím jméno');
		$form->addText('latitude', 'Zeměpisná šířka (N)')
			->addCondition($form::FILLED)->addRule($form::FLOAT, 'Zadaná souřadnice je ve špatném tvaru');
		$longitude = $form->addText('longitude', 'Zeměpisná výška (E)');
		$longitude
			->addCondition($form::FILLED)->addRule($form::FLOAT, 'Zadaná souřadnice je ve špatném tvaru');

		$form->addSubmit('_gps', 'Detekovat GPS')->onClick[] = $this->gpsClick;
		$form->addSaveButton('Save');
	}


	public function gpsClick(Button $button)
	{
		$values = $button->form->values;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->getUrl($values['name']));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$data = curl_exec($ch);
		curl_close($ch);

		if (!$data) {
			$button->form->addError('Nepodařilo se načíst data');
			die();
			return;
		}

		$data = json_decode($data, true);

		if (!$data) {
			$button->form->addError('Nepodařilo se zpracovat data');
			die();
			return;
		}

		if (!isset($data['results'][0]['geometry']['viewport'])) {
			$button->form->addError('Nepodařilo se nalézt GPS');
			die();
			return;
		}

		$viewport = $data['results'][0]['geometry']['viewport'];

		$button->form['latitude']->value = $button->form->data->latitude = ($viewport['northeast']['lat'] + $viewport['southwest']['lat']) / 2;
		$button->form['longitude']->value = $button->form->data->longitude = ($viewport['northeast']['lng'] + $viewport['southwest']['lng']) / 2;
	}


	/**
	 * @param $search
	 * @return string
	 */
	private function getUrl($search)
	{
		return 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($search).',Czech+republic&sensor=true&key=AIzaSyBCOzFDHPUbaZBV8WEfkBXLBg-50R1XZ_M';
	}



}
