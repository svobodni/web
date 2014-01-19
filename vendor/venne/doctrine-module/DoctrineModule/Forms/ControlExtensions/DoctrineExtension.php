<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DoctrineModule\Forms\ControlExtensions;

use Kdyby\Replicator\Container;
use Nette\Object;
use Venne\Forms\IControlExtension;
use Venne\Forms\Form;
use DoctrineModule\Forms\Controls\ManyToMany;
use DoctrineModule\Forms\Controls\ManyToOne;
use DoctrineModule\Forms\Mappers\EntityMapper;

/**
 * @author     Josef Kříž
 */
class DoctrineExtension extends Object implements IControlExtension
{

	/**
	 * @param Form $form
	 */
	public function check($form)
	{
		if (!$form->getMapper() instanceof EntityMapper) {
			throw new \Nette\InvalidArgumentException("Form mapper must be instanceof 'EntityMapper'. '" . get_class($form->getMapper()) . "' is given.");
		}

		if (!$form->getData() instanceof \DoctrineModule\Entities\IEntity) {
			throw new \Nette\InvalidArgumentException("Form data must be instanceof 'IEntity'. '" . get_class($form->getData()) . "' is given.");
		}
	}


	/**
	 * @return array
	 */
	public function getControls(Form $form)
	{
		$this->check($form);

		return array(
			'one', 'many', 'manyToOne', 'manyToMany', 'oneToMany', 'oneToOne'
		);
	}


	/**
	 * @param $form
	 * @param $name
	 * @return \DoctrineModule\Forms\Containers\EntityContainer
	 */
	public function addOne($form, $name)
	{
		$entity = $form->getMapper()->getRelated($form, $name);
		return $form[$name] = new \DoctrineModule\Forms\Containers\EntityContainer($entity);
	}


	/**
	 * @param $form
	 * @param $name
	 * @param $containerFactory
	 * @param null $entityFactory
	 * @return \DoctrineModule\Forms\Containers\CollectionContainer
	 */
	public function addMany($form, $name, $containerFactory, $entityFactory = NULL)
	{
		Container::register();

		$collection = $form->getMapper()->getCollection($form->getData(), $name);
		$form[$name] = $control = new \DoctrineModule\Forms\Containers\CollectionContainer($collection, $containerFactory);
		if ($entityFactory) {
			$control->setEntityFactory($entityFactory);
		}
		$control->containerClass = 'DoctrineModule\Forms\Containers\EntityContainer';
		return $control;
	}


	/**
	 * @param $form
	 * @param $name
	 * @param null $label
	 * @param null $size
	 * @return ManyToOne
	 */
	public function  addManyToOne($form, $name, $label = NULL, $size = NULL)
	{
		$form[$name] = $control = new ManyToOne('ManyToOne', $label, $size);
		$control->setPrompt("---------");
		return $form[$name];
	}


	/**
	 * @param $form
	 * @param $name
	 * @param null $label
	 * @param null $size
	 * @return ManyToOne
	 */
	public function addOneToOne($form, $name, $label = NULL, $size = NULL)
	{
		$form[$name] = $control = new ManyToOne('OneToOne', $label, $size);
		$control->setPrompt("---------");
		return $form[$name];
	}


	/**
	 * @param $form
	 * @param $name
	 * @param null $label
	 * @param null $size
	 * @return ManyToMany
	 */
	public function addManyToMany($form, $name, $label = NULL, $size = NULL)
	{
		$form[$name] = $control = new ManyToMany('ManyToMany', $label, $size);
		return $form[$name];
	}


	/**
	 * @param $form
	 * @param $name
	 * @param null $label
	 * @param null $size
	 * @return ManyToMany
	 */
	public function addOneToMany($form, $name, $label = NULL, $size = NULL)
	{
		$form[$name] = $control = new ManyToMany('OneToMany', $label, $size);
		return $form[$name];
	}
}
