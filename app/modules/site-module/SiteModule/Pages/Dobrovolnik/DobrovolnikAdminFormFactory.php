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
use Nette\Utils\Html;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class DobrovolnikAdminFormFactory extends FormFactory
{

	protected function getControlExtensions()
	{
		return array(
			new \DoctrineModule\Forms\ControlExtensions\DoctrineExtension(),
			new \CmsModule\Content\ControlExtension(),
			new \FormsModule\ControlExtensions\ControlExtension(),
			new \CmsModule\Content\Forms\ControlExtensions\ControlExtension(),
		);
	}


	/**
	 * @param Form $form
	 */
	public function configure(Form $form)
	{
		$form->addGroup('Kontaktní údaje');
		$form->addText('name', 'Jméno')
			->addRule($form::FILLED, 'Vyplňte prosím jméno');
		$form->addText('surname', 'Příjmení')
			->addRule($form::FILLED, 'Vyplňte prosím příjmení');
		$form->addText('email', 'E-mail')
			->addCondition($form::FILLED)
			->addRule($form::EMAIL, 'Zadejte prosím správný tvar emailové adresy');
		$form->addText('phone', 'Telefon');
		$form->addText('city', 'Obec');
		$form->addContentTags('villages', 'Obce');
		$gps = $form->addCheckbox('gps', 'Zadat přesnou GPS polohu');
		$gps
			->addCondition($form::EQUAL, TRUE)->toggle('group-gps');

		$form->addGroup('Určení polohy ...')->setOption('id', 'group-gps');
		$form->addText('latitude', 'Zeměpisná šířka (N)')
			->addConditionOn($gps, $form::EQUAL, TRUE)
			->addCondition($form::FILLED)->addRule($form::FLOAT, 'Zadaná souřadnice je ve špatném tvaru');
		$longitude = $form->addText('longitude', 'Zeměpisná výška (E)');
		$longitude
			->addConditionOn($gps, $form::EQUAL, TRUE)
			->addCondition($form::FILLED)->addRule($form::FLOAT, 'Zadaná souřadnice je ve špatném tvaru');

		$label = Html::el();

		$label
			->setHtml('Vaše přesná poloha nám umožní lépe koordinovat kampaň. Jestliže Váš prohlížeč nepodporuje automatickou detekci souřadnic, máte možnost zjistit ji na mapovém serveru <a href="http://mapy.cz" target="_blank">mapy.cz</a> (pravým tlačítkem na Vaši pozici a zvolte "Co je zde?").');

		$longitude->setOption('description', $label);
		$form->addSubmit('_gps', 'Detekovat GPS souřadnice')
			->setValidationScope(FALSE);

		$form->addGroup('Rád bych pomohl ...');
		$form->addCheckbox('homework', 'Ze svého domova nebo po síti')
			->setOption('description', 'Rád nabídnu k inzerci svůj web či blog nebo budu diskutovat či nabízím své jiné schopnosti.');
		$form->addCheckbox('contactCampaign', 'Při kontaktní kampani')
			->setOption('description', 'Rád se zúčastním kontaktní kampaně na ulici, budu s lídrem a kandidáty rozdávat letáky a diskutovat s lidmi.');
		$form->addCheckbox('advertisingArea', 'Nabídkou reklamních ploch')
			->setOption('description', 'Rád poskytnu fasádu či plot u svého domu, který je na viditelném a frekventovaném místě.');
		$form->addCheckbox('distributionOfLeaflets', 'Roznosem letáků do schránek')
			->setOption('description', 'Rád roznesu letáky do schránek v okolí svého bydliště.');
		$form->addCheckbox('distributionOfPosters', 'Výlepem plakátů v okolních obcích')
			->setOption('description', 'Rád objedu okolní obce a jejich plakátovací plochy a umístím na ně předvolební plakáty');
		$form->addTextArea('ownHelp', 'Jinou formou')->getControlPrototype()->placeholder = 'Jinou formou';

		$form->addGroup('Pomohu z domu nebo po síti ...');
		$form->addCheckbox('blog', 'Mám web či blog, kde mohu nabídnout inzerci')
			->setOption('description', 'Provozuji web, na který mohu umístit jeden z volebních bannerů. Píšu blog, ve kterém zmiňuji Svobodné.');
		$form->addCheckbox('discussion', 'Rád diskutuji s lidmi a komentuji aktuality')
			->setOption('description', 'Pohybuji se často na sociálních sítích či v diskusích pod články a chci uvádět pravdivé informace o Svobodných.');
		$form->addTextArea('skills', 'Ovládám něco, co by mohlo straně pomoci')->getControlPrototype()->placeholder = 'Ovládám něco, co by mohlo straně pomoci';

		$form->addGroup('Nabízím reklamní plochu či prostory ...');
		$form->addCheckbox('adFacade', 'Mám fasádu či plot na viditelném a frekventovaném místě')
			->setOption('description', 'Na můj plot či fasádu si rád umístit reklamní banner o rozměru 200 x 100 cm. Případně nabízím atypickou plochu.');
		$form->addCheckbox('adWindow', 'Vylepím si za okno plakát')
			->setOption('description', 'Mohu si za své okno vylepit jeden z plakátů Svobodných a upozornit tak sousedy a kolemjdoucí.');
		$form->addCheckbox('adShop', 'Vylepím si plakát do výlohy prodejny')
			->setOption('description', 'Vlastním (nebo znám příznivce, který vlastní) prodejnu a je ochotný si vylepit tematicky zaměřený plakát do výlohy.');
		$form->addCheckbox('adCar', 'Nabízím k polepu auto (celé auto, zadní sklo nebo malá samolepka)')
			->setOption('description', 'Mám k dispozici auto, které mohu nechat polit, případně nabízím zadní sklo nebo si na auto nalepím samolepku.');

		$form->addGroup('Jsem ochotný ...');
		$form->addCheckbox('provideCar', 'Zapůjčit automobil')
			->setOption('description', 'Jsem ochotný zapůjčit svou dodávku či osobní automobil pro kontaktní týmy či distribuci materiálů.');
		$form->addCheckbox('providePub', 'Zapůjčit svou hospůdku pro konání besed s lídry či setkání příznivců')
			->setOption('description', 'Vlastním hospodu či konferenční prostory vhodné pro konání besed s lídry nebo pro setkání příznivců Svobodných.');
		$form->addCheckbox('provideAccommodation', 'Poskytnout ubytování pro členy kontaktních týmů')
			->setOption('description', 'Jsem ochotný ubytovat na noc členy kontaktního týmu, kteří v kampani objíždějí celou republiku.');

		$form->addGroup('Pomohu jinak ...');
		$form->addTextArea('otherwiseHelp', ' ')->getControlPrototype()->placeholder = 'Pomohu jinak ...';

		$confirm = $form->addCheckbox('_confirm', 'Odesláním formuláře souhlasím se zpracováním osobních údajů dle příslušné legislativy a se zařazením do seznamu dobrovolníků Strany svobodných občanů.');
		$confirm->setDefaultValue(TRUE);
		$confirm
			->addRule($form::FILLED, 'Potvrďte prosím souhlas se zpracováním osobních údajů.');
		$confirm->getLabelPrototype()->style[] = 'margin-top: 40px;';

		$submit = $form->addSaveButton('Odeslat');
		$submit->getControlPrototype()->class = 'btn-primary btn-block';
		$submit->getControlPrototype()->style[] = 'font-size: 25px; font-style: italic;';
	}


	public function handleLoad(Form $form)
	{
		if ($form->data->latitude) {
			$form['gps']->value = TRUE;
		}
	}


	public function handleSave(Form $form)
	{
		if (!$form['gps']->value) {
			$form->data->latitude = NULL;
			$form->data->longitude = NULL;
		}

		parent::handleSave($form);
	}

}
