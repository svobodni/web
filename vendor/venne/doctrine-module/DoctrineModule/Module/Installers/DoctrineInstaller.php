<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DoctrineModule\Module\Installers;

use Venne;
use Nette\Reflection\ClassType;
use Venne\Utils\File;
use Nette\DI\Container;
use Venne\Module\IModule;
use Doctrine\ORM\EntityManager;
use Nette\Config\Adapters\NeonAdapter;
use Venne\Module\Installers\BaseInstaller;
use Venne\Module\ModuleManager;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class DoctrineInstaller extends BaseInstaller
{

	/** @var Container */
	protected $context;

	/** @var string */
	protected $resourcesDir;

	/** @var string */
	protected $configDir;

	/** @var EntityManager */
	protected $entityManager;


	/**
	 * @param \Nette\DI\Container $context
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 */
	public function __construct(Container $context, EntityManager $entityManager)
	{
		$this->context = $context;
		$this->resourcesDir = $context->parameters['resourcesDir'];
		$this->configDir = $context->parameters['configDir'];
		$this->entityManager = $entityManager;
	}


	/**
	 * @param \Venne\Module\IModule $module
	 */
	public function install(IModule $module)
	{
		if (!$this->context->hasService('doctrine') || !$this->context->doctrine->createCheckConnection()) {
			throw new \Exception('Database connection not found!');
		}

		$classes = $this->getClasses($module);

		$metadata = array();
		foreach ($classes as $class) {
			$metadata[] = $this->entityManager->getClassMetadata($class);
		}

		$tool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
		$this->entityManager->getConnection()->beginTransaction();
		try {
			foreach ($this->getAllClasses() as $class) {
				$metadata[] = $this->entityManager->getClassMetadata($class);
			}
			$tool->updateSchema($metadata);
			$this->entityManager->getConnection()->commit();
		} catch (Exception $e) {
			$this->entityManager->getConnection()->rollback();
			$this->entityManager->close();
			throw $e;
		}

		$this->cleanCache();
	}


	/**
	 * @param \Venne\Module\IModule $module
	 */
	public function uninstall(IModule $module)
	{
		if (!$this->context->hasService('doctrine') || !$this->context->doctrine->createCheckConnection()) {
			throw new \Exception('Database connection not found!');
		}

		$classes = $this->getClasses($module);

		$metadata = array();
		foreach ($classes as $class) {
			$metadata[] = $this->entityManager->getClassMetadata($class);
		}

		$tool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
		$this->entityManager->getConnection()->beginTransaction();
		try {
			foreach ($classes as $class) {
				$repository = $this->entityManager->getRepository($class);
				foreach ($repository->findAll() as $entity) {
					$repository->delete($entity);
				}
			}

			$tool->dropSchema($metadata);
			$this->entityManager->getConnection()->commit();
		} catch (Exception $e) {
			$this->entityManager->getConnection()->rollback();
			$this->entityManager->close();
			throw $e;
		}

		$this->cleanCache();
	}


	/**
	 * @param \Venne\Module\IModule $module
	 * @return array
	 * @throws \Exception
	 */
	protected function getClasses(IModule $module)
	{
		// find files
		$robotLoader = new \Nette\Loaders\RobotLoader;
		$robotLoader->setCacheStorage(new \Nette\Caching\Storages\MemoryStorage());
		$robotLoader->addDirectory($module->getPath());
		$robotLoader->register();
		$entities = $robotLoader->getIndexedClasses();

		// paths
		$paths = array();
		foreach (\Nette\Utils\Finder::findFiles('*Entity.php')->from($module->getPath())->exclude('vendor/*')->exclude('tests/*') as $file) {
			$paths[] = $file->getPath();
		}
		$this->entityManager->getConfiguration()->getMetadataDriverImpl()->addPaths($paths);

		// classes
		$classes = array();
		foreach ($entities as $class => $item) {
			if (\Nette\Reflection\ClassType::from($class)->hasAnnotation('ORM\Entity')) {
				$classes[] = $class;
			}
		}

		$robotLoader->unregister();

		return $classes;
	}


	/**
	 * @param \Venne\Module\IModule $module
	 * @return array
	 * @throws \Exception
	 */
	protected function getAllClasses()
	{
		// find files
		$robotLoader = new \Nette\Loaders\RobotLoader;
		$robotLoader->setCacheStorage(new \Nette\Caching\Storages\MemoryStorage());
		foreach ($this->context->parameters['modules'] as $name => $item) {
			if ($item[ModuleManager::MODULE_STATUS] === ModuleManager::STATUS_INSTALLED) {
				$path = $this->context->expand($item[ModuleManager::MODULE_PATH]) . '/' . ucfirst($name) . 'Module';
				if (file_exists($path)) {
					$robotLoader->addDirectory($path);
				}
			}
		}
		$robotLoader->register();
		$entities = $robotLoader->getIndexedClasses();

		// classes
		$classes = array();
		foreach ($entities as $class => $item) {
			if (\Nette\Reflection\ClassType::from('\\' . $class)->hasAnnotation('ORM\Entity')) {
				$classes[] = $class;
			}
		}

		$robotLoader->unregister();

		return $classes;
	}


	protected function cleanCache()
	{
		$this->entityManager->getConfiguration()->getMetadataCacheImpl()->deleteAll();
	}
}

