<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DoctrineModule\Mapping;

use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use Nette\Utils\Strings;


/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class VenneNamingStrategy extends DefaultNamingStrategy
{

	public function joinTableName($sourceEntity, $targetEntity, $propertyName = null)
	{
		if (Strings::endsWith($targetEntity, '::dynamic')) {
			$targetEntity = $this->detectTargetEntity($sourceEntity, $propertyName);
		}

		if (Strings::endsWith($sourceEntity, '::dynamic')) {
			$sourceEntity = $this->detectTargetEntity($targetEntity, $propertyName);
		}

		return strtolower($this->classToNamespace($sourceEntity)) . '_' . parent::joinTableName($sourceEntity, $targetEntity, $propertyName);
	}


	/**
	 * {@inheritdoc}
	 */
	public function joinKeyColumnName($entityName, $referencedColumnName = null)
	{
		return strtolower($this->classToTableName($entityName) . '_' .
			($referencedColumnName ?: $this->referenceColumnName()));
	}


	/**
	 * {@inheritdoc}
	 */
	protected function classToNamespace($className)
	{
		return substr($className, 0, strpos($className, '\\') - 6);
	}


	/**
	 * @param string $entity
	 * @param string $property
	 * @return string
	 */
	private function detectTargetEntity($entity, $property)
	{
		$method = 'get' . ucfirst($property) . 'Name';
		return call_user_func(array($entity, $method));
	}

}
