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

use CmsModule\Administration\Components\AdminGrid\AdminGrid;
use CmsModule\Content\SectionControl;
use CmsModule\Pages\Users\UserEntity;
use Grido\DataSources\Doctrine;
use Nette\Utils\Html;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class SummaryControl extends SectionControl
{

	/** @var DobrovolnikRepository */
	protected $dobrovolnikRepository;

	/** @var DobrovolnikFormFactory */
	protected $formFactory;


	/**
	 * @param DobrovolnikRepository $dobrovolnikRepository
	 * @param DobrovolnikAdminFormFactory $formFactory
	 */
	public function __construct(DobrovolnikRepository $dobrovolnikRepository, DobrovolnikAdminFormFactory $formFactory)
	{
		parent::__construct();

		$this->dobrovolnikRepository = $dobrovolnikRepository;
		$this->formFactory = $formFactory;
	}


	protected function createComponentTable()
	{
		$admin = new AdminGrid($this->dobrovolnikRepository);

		// columns
		$table = $admin->getTable();
		$table->setModel(new Doctrine($this->dobrovolnikRepository->createQueryBuilder('a')
				->addSelect('r')
				->innerJoin('a.route', 'r')
				->andWhere('a.extendedPage = :page')->setParameter('page', $this->extendedPage->id),
			array('created' => 'r.created')
		));
		$table->setDefaultSort(array('created' => 'DESC'));

		$table->addColumnText('surname', 'Surname')
			->setCustomRender(function(DobrovolnikEntity $entity) {
				$el = Html::el('a')->setText($entity->surname);
				$el->title = $entity->surname . ' ' . $entity->name . '  Město: ' . $entity->city . '' . '  Tel.: ' . $entity->phone . '  Email: ' . $entity->email;
				$el->href = 'mailto:' . $entity->email;
				$el->target = '_blank';
				return $el;
			})
			->setSortable()
			->getCellPrototype()->width = '15%';
		$table->getColumn('surname')
			->setFilterText()->setSuggestion();

		$table->addColumnText('name', 'Name')
			->setSortable()
			->getCellPrototype()->width = '15%';
		$table->getColumn('name')
			->setFilterText()->setSuggestion();

		$items = array(
			'homework' => array('HomeW' => 'Ze svého domova nebo po síti'),
			'contactCampaign' => array('Campaign' => 'Při kontaktní kampani'),
			'advertisingArea' => array('Plocha' => 'Nabídkou reklamních ploch'),
			'distributionOfLeaflets' => array('Roznos' => 'Roznosem letáků do schránek'),
			'distributionOfPosters' => array('Výlep' => 'Výlepem plakátů v okolních obcích'),
			'blog' => array('Blog' => 'Mám web či blog, kde mohu nabídnout inzerci'),
			'discussion' => array('Disc.' => 'Rád diskutuji s lidmi a komentuji aktuality'),
			'adFacade' => array('Fasada' => 'Mám fasádu či plot na viditelném a frekventovaném místě'),
			'adWindow' => array('Okno' => 'Vylepím si za okno plakát'),
			'adShop' => array('Shop' => 'Vylepím si plakát do výlohy prodejny'),
			'adCar' => array('Auto' => 'Nabízím k polepu auto (celé auto, zadní sklo nebo malá samolepka)'),
			'provideCar' => array('Půjčit Auto' => 'Zapůjčit automobil'),
			'providePub' => array('Pub' => 'Zapůjčit svou hospůdku pro konání besed s lídry či setkání příznivců'),
			'provideAccommodation' => array('Azyl' => 'Poskytnout ubytování pro členy kontaktních týmů'),
		);
		foreach ($items as $key => $item) {
			$el = Html::el('small')->setText(key($item));
			$el->title = reset($item);
			$el->style = 'font-size: 60%';
			$table->addColumnText($key, $el)
				->setSortable()
				->getCellPrototype()->width = '4%';
			$table->getColumn($key)
				->setFilterText()->setSuggestion();
		}


		$items = array(
			'ownHelp' => array('Txt' => 'Jinou formou'),
			'skills' => array('Txt' => 'Ovládám něco, co by mohlo straně pomoci'),
			'otherwiseHelp' => array('Txt' => 'Pomohu jinak'),
		);
		foreach ($items as $key => $item) {
			$el = Html::el('small')->setText(key($item));
			$el->title = reset($item);
			$el->style = 'font-size: 60%';
			$table->addColumnText($key, $el)
				->setCustomRender(function(DobrovolnikEntity $entity) use ($key){
					$el = Html::el('span')->setText($entity->{$key} ? 'T' : '');
					$el->title = $entity->{$key};
					return $el;
				})
				->setSortable()
				->getCellPrototype()->width = '4%';
			$table->getColumn($key)
				->setFilterText()->setSuggestion();
		}


		$_this = $this;
		$repository = $this->dobrovolnikRepository;
		$formFactory = $this->formFactory;
		$entity = $this->extendedPage;
		$form = $admin->createForm($formFactory, '', function () use ($repository, $entity, $_this) {
			$entity = $repository->createNew(array($entity));
			if ($_this->presenter->user->identity instanceof UserEntity) {
				$entity->route->author = $_this->presenter->user->identity;
			}
			return $entity;
		}, \CmsModule\Components\Table\Form::TYPE_FULL);

		$table->addAction('edit', 'Edit');
		$admin->connectFormWithAction($form, $table->getAction('edit'), $admin::MODE_PLACE);

		return $admin;
	}

	public function render()
	{
		$this->template->render();
	}
}
