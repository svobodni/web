<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DoctrineModule\Forms\Controls;

use Venne;
use Nette\Forms\Controls\BaseControl;
use Nette;

/**
 * @author     Josef Kříž
 */
class ManyToOne extends BaseControl
{

	/** @var array */
	protected $items = array();

	/** @var bool */
	protected $itemsLoaded = FALSE;

	/** @var array */
	protected $allowed = array();

	/** @var bool */
	private $prompt = FALSE;

	/** @var bool */
	private $useKeys = TRUE;

	/** @var string */
	protected $type;

	/** @var array */
	protected $criteria = array();

	/** @var array */
	protected $orderBy = array();

	/** @var int */
	protected $limit;

	/** @var int */
	protected $offset;

	/** @var \Doctrine\ORM\QueryBuilder */
	protected $query;

	/** @var array */
	protected $dependOn;


	/**
	 * @param  string  label
	 * @param  array   items from which to choose
	 * @param  int     number of rows that should be visible
	 */
	public function __construct($type, $label = NULL, $size = NULL)
	{
		parent::__construct($label);

		$this->control->setName('select');
		$this->control->size = $size > 1 ? (int)$size : NULL;

		$this->type = $type;
	}


	protected function loadEntities()
	{
		if ($this->query) {
			$items = $this->query->getQuery()->getResult();
		} else {
			$ref = $this->getParent()->data->getReflection()->getProperty($this->name)->getAnnotation('ORM\\' . $this->type);

			$class = $ref["targetEntity"];
			if (substr($class, 0, 1) != "\\") {
				$class = "\\" . $this->getParent()->data->getReflection()->getNamespaceName() . "\\" . $class;
			}

			$items = $this->getParent()->form->mapper->entityManager->getRepository($class)->findBy($this->criteria, $this->orderBy, $this->limit, $this->offset);
		}

		$this->setItems($items);
	}


	public function setValue($value)
	{
		if ($value instanceof \DoctrineModule\Entities\IEntity) {
			return parent::setValue($value->id);
		}
	}


	/**
	 * Loads HTTP data.
	 *
	 * @return void
	 */
	public function loadHttpData()
	{
		$path = explode('[', strtr(str_replace(array('[]', ']'), '', $this->getHtmlName()), '.', '_'));
		$this->value = (Nette\Utils\Arrays::get($this->getForm()->getHttpData(), $path, NULL));
	}


	/**
	 * Sets control's default value.
	 *
	 * @param  mixed
	 * @return BaseControl  provides a fluent interface
	 */
	public function setDefaultValue($value)
	{
		$form = $this->getForm(FALSE);
		if (!$form || !$form->isAnchored() || !$form->isSubmitted()) {
			$this->setValue($value);
		}
		return $this;
	}


	public function getValue()
	{
		if (!$this->itemsLoaded) {
			$this->loadEntities();
			$this->itemsLoaded = TRUE;
		}

		foreach ($this->items as $item) {
			if ($item instanceof \DoctrineModule\Entities\IEntity) {
				if ($item->id == $this->value) {
					return $item;
				}
			}
		}
		return NULL;
	}


	/**
	 * Returns selected item key (not checked).
	 *
	 * @return mixed
	 */
	public function getRawValue()
	{
		return is_scalar($this->value) ? $this->value : NULL;
	}


	/**
	 * Generates control's HTML element.
	 *
	 * @return Nette\Utils\Html
	 */
	public function getControl()
	{
		if (!$this->itemsLoaded) {
			$this->loadEntities();
			$this->itemsLoaded = TRUE;
		}

		$control = parent::getControl();
		$option = Nette\Utils\Html::el('option');

		if ($this->prompt !== NULL) {
			$control->add($this->prompt instanceof Nette\Utils\Html
					? $this->prompt->value('')
					: (string)$option->value('')->setText($this->translate((string)$this->prompt))
			);
		}

		$selected = (array)$this->getRawValue();

		foreach ($this->items as $key => $value) {
			if (!is_array($value)) {

				if ($value instanceof \DoctrineModule\Entities\IEntity) {
					$value = array($value->id => $value);
				} else {
					$value = array($value => $value);
				}

				$dest = $control;
			} else {
				$dest = $control->create('optgroup')->label($this->translate($key));
			}

			foreach ($value as $value2) {
				if ($value2 instanceof \DoctrineModule\Entities\IEntity) {
					$key2 = $value2->id;
				} else {
					$key2 = $value2;
				}

				if ($value2 instanceof Nette\Utils\Html) {
					$dest->add((string)$value2->selected(isset($selected[$key2])));
				} else {
					$value2 = (string)$this->translate($value2);
					$dest->add((string)$option->value($key2 === $value2 ? "" : $key2)->selected(in_array($key2, $selected))->setText($value2));
				}
			}
		}
		return $control;
	}


	/**
	 * Has been any item selected?
	 *
	 * @return bool
	 */
	public function isFilled()
	{
		$value = $this->getValue();
		return is_array($value) ? count($value) > 0 : $value !== NULL;
	}


	/**
	 * Ignores the first item in select box.
	 *
	 * @param  string
	 * @return SelectBox  provides a fluent interface
	 */
	public function setPrompt($prompt)
	{
		$this->prompt = $prompt;
		return $this;
	}


	/** @deprecated */
	function skipFirst($v = NULL)
	{
		trigger_error(__METHOD__ . '() is deprecated; use setPrompt() instead.', E_USER_WARNING);
		return $this->setPrompt($v);
	}


	/**
	 * Is first item in select box ignored?
	 *
	 * @return bool
	 */
	final public function getPrompt()
	{
		return $this->prompt;
	}


	/**
	 * Are the keys used?
	 *
	 * @return bool
	 */
	final public function areKeysUsed()
	{
		return $this->useKeys;
	}


	/**
	 * Sets items from which to choose.
	 *
	 * @param  array
	 * @return SelectBox  provides a fluent interface
	 */
	protected function setItems(array $items, $useKeys = TRUE)
	{
		$this->items = $items;
		$this->allowed = array();
		$this->useKeys = (bool)$useKeys;

		foreach ($items as $key => $value) {
			if (!is_array($value)) {
				$value = array($key => $value);
			}

			foreach ($value as $key2 => $value2) {
				if (!$this->useKeys) {
					if (!is_scalar($value2)) {
						throw new Nette\InvalidArgumentException("All items must be scalar.");
					}
					$key2 = $value2;
				}

				if (isset($this->allowed[$key2])) {
					throw new Nette\InvalidArgumentException("Items contain duplication for key '$key2'.");
				}

				$this->allowed[$key2] = $value2;
			}
		}
		return $this;
	}


	/**
	 * Returns items from which to choose.
	 *
	 * @return array
	 */
	protected function getItems()
	{
		return $this->items;
	}


	/**
	 * Returns selected value.
	 *
	 * @return string
	 */
	public function getSelectedItem()
	{
		if (!$this->useKeys) {
			return $this->getValue();
		} else {
			$value = $this->getValue();
			return $value === NULL ? NULL : $this->allowed[$value];
		}
	}


	/**
	 * @param array $criteria
	 */
	public function setCriteria($criteria)
	{
		$this->criteria = $criteria;
		$this->itemsLoaded = FALSE;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getCriteria()
	{
		return $this->criteria;
	}


	/**
	 * @param int $offset
	 */
	public function setOffset($offset)
	{
		$this->offset = $offset;
		$this->itemsLoaded = FALSE;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getOffset()
	{
		return $this->offset;
	}


	/**
	 * @param array $orderBy
	 */
	public function setOrderBy($orderBy)
	{
		$this->orderBy = $orderBy;
		$this->itemsLoaded = FALSE;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getOrderBy()
	{
		return $this->orderBy;
	}


	/**
	 * @param int $limit
	 */
	public function setLimit($limit)
	{
		$this->limit = $limit;
		$this->itemsLoaded = FALSE;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
	}


	/**
	 * @param \Doctrine\ORM\QueryBuilder $query
	 */
	public function setQuery($query)
	{
		$this->query = $query;
		$this->itemsLoaded = FALSE;
		return $this;
	}


	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getQuery()
	{
		return $this->query;
	}


	/**
	 * @param \Nette\Forms\IControl $control
	 * @param $name
	 * @return ManyToOne
	 */
	public function  setDependOn(\Nette\Forms\IControl $control, $name = NULL)
	{
		$_this = $this;
		$this->dependOn = array($control, $name ? : $control->name);

		$this->criteria = array($name => -1);

		$this->form->addSubmit($this->name . '_reload', 'reload')->setValidationScope(FALSE);

		$control->form->onBeforeRender[] = function ($form) use ($_this, $control) {
			$control->getControlPrototype()->onChange = "$('#frm{$form->name}-{$_this->name}_reload').click();";
		};

		$control->form->onSave[] = function ($form) use ($_this, $control, $name) {
			$_this->setCriteria(array($name => $control->value));
		};

		return $this;
	}
}
