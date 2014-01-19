<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DoctrineModule\Forms;

use Doctrine\DBAL\DBALException;
use DoctrineModule\Forms\Mappers\EntityMapper;
use Nette\InvalidArgumentException;
use Venne\Forms\Form;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormFactory extends \Venne\Forms\FormFactory
{

	/** @var EntityMapper */
	protected $mapper;

	/** @var array */
	public $onCatchError;


	/**
	 * @param EntityMapper $mapper
	 */
	public function __construct(EntityMapper $mapper)
	{
		$this->mapper = $mapper;
	}


	protected function getMapper()
	{
		return $this->mapper;
	}


	protected function getControlExtensions()
	{
		return array(
			new \DoctrineModule\Forms\ControlExtensions\DoctrineExtension(),
		);
	}


	public function handleSave(Form $form)
	{
		if ($form->hasSaveButton() && $form->isSubmitted() === $form->getSaveButton()) {
			try {
				$this->mapper->entityManager->getRepository(get_class($form->data))->save($form->data);
			} catch (\Exception $e) {
				$ok = true;

				if (is_array($this->onCatchError) || $this->onCatchError instanceof \Traversable) {
					foreach ($this->onCatchError as $handler) {
						if (\Nette\Callback::create($handler)->invokeArgs(array($form, $e))) {
							$ok = false;
							break;
						}
					}
				} elseif ($this->onCatchError !== NULL) {
					$class = get_class($this);
					throw new \Nette\UnexpectedValueException("Property $class::onCatchError must be array or NULL, " . gettype($this->onCatchError) . " given.");
				}

				if ($ok) {
					throw $e;
				}
			}
		}
	}


	public function handleCatchError(Form $form, $e)
	{
		if ($e instanceof InvalidArgumentException) {
			$form->addError($e->getMessage());
			return TRUE;
		} else if ($e instanceof DBALException && strpos($e->getMessage(), 'Duplicate entry') !== false) {
			$form->addError('Duplicate entry');
			return TRUE;
		}
	}


	protected function attachHandlers($form)
	{
		if (method_exists($this, 'handleCatchError')) {
			$this->onCatchError[] = callback($this, 'handleCatchError');
		}

		return parent::attachHandlers($form);
	}
}