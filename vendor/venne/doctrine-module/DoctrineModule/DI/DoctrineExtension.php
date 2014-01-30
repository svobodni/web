<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace DoctrineModule\DI;

use Nette\DI\ContainerBuilder;
use Nette\Reflection\ClassType;
use Nette\Utils\Strings;
use Venne;
use Venne\Config\CompilerExtension;
use Venne\Module\ModuleManager;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class DoctrineExtension extends CompilerExtension
{

	/** @var bool */
	protected static $isConnected;

	const CACHE_CLASS_NETTE = 'DoctrineModule\Cache';

	const CACHE_CLASS_APC = 'Doctrine\Common\Cache\ApcCache';

	const CACHE_CLASS_XCACHE = 'Doctrine\Common\Cache\XcacheCache';

	const CACHE_CLASS_ARRAY = 'Doctrine\Common\Cache\ArrayCache';

	const CACHE_CLASS_REDIS = 'Doctrine\Common\Cache\RedisCache';

	const CACHE_CLASS_MEMCACHE = 'Doctrine\Common\Cache\MemcacheCache';

	/** @var array */
	protected static $caches = array(
		self::CACHE_CLASS_NETTE => 'Nette cache',
		self::CACHE_CLASS_APC => 'APC cache',
		self::CACHE_CLASS_XCACHE => 'XCache cache',
		self::CACHE_CLASS_ARRAY => 'Array cache',
		self::CACHE_CLASS_REDIS => 'Redis cache',
		self::CACHE_CLASS_MEMCACHE => 'Memcache cache',
	);

	const CONNECTIONS_PREFIX = 'connections',
		ENTITY_MANAGERS_PREFIX = 'entityManagers',
		SCHEMA_MANAGERS_PREFIX = 'schemaManagers',
		EVENT_MANAGERS_PREFIX = 'eventManagers',
		CONFIGURATIONS_PREFIX = 'configurations';

	/** @var array */
	public $configurationDefaults = array(
		'annotationReader' => array(
			'namespace' => 'Doctrine\ORM\Mapping',
		),
		'proxiesDir' => '%tempDir%/proxies',
		'proxiesNamespace' => 'Proxies',
		'mappingDriver' => 'annotation',
	);

	/** @var array */
	public $schemaManagerDefaults = array(
		'connection' => 'default',
	);

	/** @var array */
	public $eventManagerDefaults = array();

	/** @var array */
	public $connectionDefaults = array(
		'debugger' => TRUE,
		'collation' => FALSE,
		'eventManager' => NULL,
		'autowired' => FALSE,
	);

	/** @var array */
	public $entityManagerDefaults = array(
		'entityDirs' => array('%appDir%'),
		'proxyDir' => '%tempDir%/proxies',
		'proxyNamespace' => 'App\Model\Proxies',
		'proxyAutogenerate' => NULL,
		'useAnnotationNamespace' => FALSE,
		'metadataFactory' => NULL,
		'resultCacheDriver' => NULL,
		'console' => FALSE,
	);

	/** @var array */
	public $defaults = array(
		'debugger' => TRUE,
		'cacheClass' => 'DoctrineModule\Cache',
		'cacheRedisHost' => 'localhost',
		'cacheRedisPort' => 6379,
		'cacheMemcacheHost' => '127.0.0.1',
		'cacheMemcachePort' => 11211,
		'configurations' => array('default' => array()),
		'eventManagers' => array('default' => array()),
		'schemaManagers' => array('default' => array()),
		'entityManagers' => array(
			'default' => array(
				'connection' => 'default'
			)
		),
		'connections' => array('default' => array()),
		'console' => array('entityManager' => 'default'),
	);

	/** @var array */
	public $metadataDriverClasses = array(
		'driverChain' => 'Doctrine\ORM\Mapping\Driver\DriverChain',
		'annotation' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
		'xml' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
		'yml' => 'Doctrine\ORM\Mapping\Driver\YamlDriver',
		'php' => 'Doctrine\ORM\Mapping\Driver\PHPDriver',
		'staticphp' => 'Doctrine\ORM\Mapping\Driver\StaticPHPDriver'
	);

	/** @var string|NULL */
	protected $consoleEntityManager;


	public function loadConfiguration()
	{
		$this->compiler->parseServices(
			$this->getContainerBuilder(),
			$this->loadFromFile(dirname(dirname(__DIR__)) . '/Resources/config/doctrine.neon')
		);

		$container = $this->getContainerBuilder();
		$this->defaults['connections']['default'] = $container->parameters['database'];
		$config = $this->getConfig($this->defaults);

		// Cache
		$cache = $container->addDefinition($this->prefix('cache'))
			->setInternal(TRUE)
			->setClass($config['cacheClass'])
			->addSetup('$service->setNamespace(?)', array(md5(__DIR__)));

		if ($config['cacheClass'] === self::CACHE_CLASS_REDIS) {
			$container->addDefinition($this->prefix('redis'))
				->setClass('Redis')
				->addSetup('connect', array($config['cacheRedisHost'], $config['cacheRedisPort']));
			$cache->addSetup('setRedis', array('@' . $this->prefix('redis')));
		} elseif ($config['cacheClass'] === self::CACHE_CLASS_MEMCACHE) {
			$container->addDefinition($this->prefix('memcache'))
				->setClass('Memcache')
				->addSetup('addServer', array($config['cacheMemcacheHost'], $config['cacheMemcachePort']));
			$cache->addSetup('setMemcache', array('@' . $this->prefix('memcache')));
		}

		//if ($config["debugger"] == "development") {
		//	$container->getDefinition("entityManagerConfig")
		//		->addSetup("setSQLLogger", "@doctrinePanel");
		//}


		// configurations
		foreach ($config["configurations"] as $name => $configuration) {
			$cfg = $configuration + $this->configurationDefaults;
			$this->processConfiguration($name, $cfg);
		}


		// connections
		foreach ($config["connections"] as $name => $connection) {
			$cfg = $connection + $this->connectionDefaults;
			$this->processConnection($name, $cfg);
		}


		// schemaManagers
		foreach ($config["schemaManagers"] as $name => $sm) {
			$cfg = $sm + $this->schemaManagerDefaults;
			$this->processSchemaManager($name, $cfg);
		}


		// eventManagers
		foreach ($config["eventManagers"] as $name => $evm) {
			$cfg = $evm + $this->eventManagerDefaults;
			$this->processEventManager($name, $cfg);
		}


		// entityManagers
		foreach ($config["entityManagers"] as $name => $em) {
			$cfg = $em + $this->entityManagerDefaults;

			if (isset($cfg['connection']) && is_array($cfg['connection'])) {
				$this->processConnection($name, $cfg['connection'] + $this->connectionDefaults);
				$cfg['connection'] = $name;
			}

			if (isset($cfg['configuration']) && is_array($cfg['configuration'])) {
				$this->processConfiguration($name, $cfg['configuration'] + $this->configurationDefaults);
				$cfg['configuration'] = $name;
			}


			$this->processEntityManager($name, $cfg);
		}

		$container->addDefinition($this->prefix('checkConnectionClass'))
			->setClass('DoctrineModule\DI\ConnectionChecker')
			->setInternal(TRUE);

		$container->addDefinition($this->prefix('checkConnectionMyFactory'))
			->setClass('DoctrineModule\DI\ConnectionCheckerFactory', array($this->prefix('@checkConnectionFactory')));

		$container->addDefinition($this->prefix('checkConnection'))
			->setFactory("@doctrine.checkConnectionClass::checkConnection")
			->setShared(FALSE);

		$container->addDefinition($this->prefix("entityFormMapper"))
			->setClass("DoctrineModule\Forms\Mappers\EntityMapper", array("@entityManager"));

		$this->processConsole();
	}


	protected function processConfiguration($name, array $config)
	{
		$container = $this->getContainerBuilder();

		$container->addDefinition($this->configurationsPrefix($name . 'AnnotationRegistry'))
			->setFactory("Doctrine\Common\Annotations\AnnotationRegistry::registerFile", array(dirname(ClassType::from('Doctrine\ORM\Version')->getFileName()) . '/Mapping/Driver/DoctrineAnnotations.php'))
			->setShared(FALSE)
			->setInternal(TRUE);
		$container->addDefinition($this->configurationsPrefix($name . 'AnnotationReader'))
			->setClass('Doctrine\Common\Annotations\AnnotationReader', array($this->configurationsPrefix('@' . $name . 'AnnotationRegistry')))
			->setShared(FALSE)
			->setInternal(TRUE);
		$container->addDefinition($this->configurationsPrefix($name . 'CachedAnnotationReader'))
			->setClass("Doctrine\Common\Annotations\CachedReader", array($this->configurationsPrefix('@' . $name . 'AnnotationReader'), "@doctrine.cache"))
			->setInternal(TRUE);

		$paths = array();
		foreach ($container->parameters['modules'] as $module) {
			if ($module[ModuleManager::MODULE_STATUS] === ModuleManager::STATUS_INSTALLED) {
				foreach (\Nette\Utils\Finder::findFiles('*Entity.php')->from($module[ModuleManager::MODULE_PATH])->exclude('vendor/*')->exclude('tests/*') as $file) {
					$paths[$file->getPath()] = TRUE;
				}
			}
		}

		$container->addDefinition($this->configurationsPrefix($name . 'AnnotationDriver'))
			->setClass("Doctrine\ORM\Mapping\Driver\AnnotationDriver", array($this->configurationsPrefix('@' . $name . 'CachedAnnotationReader'), array_keys($paths)))
			->addSetup('setFileExtension', 'Entity.php')
			->setInternal(TRUE);


		$paths = array();
		foreach ($container->parameters['modules'] as $module) {
			if ($module[ModuleManager::MODULE_STATUS] === ModuleManager::STATUS_INSTALLED) {
				foreach (\Nette\Utils\Finder::findFiles('*.dcm.yml')->from($module[ModuleManager::MODULE_PATH])->exclude('vendor/*') as $file) {
					$paths[$file->getPath()] = TRUE;
				}
			}
		}

		$container->addDefinition($this->configurationsPrefix($name . 'YmlDriver'))
			->setClass("Doctrine\ORM\Mapping\Driver\YamlDriver", array(array_keys($paths)))
			->setInternal(TRUE);

		$container->addDefinition($this->configurationsPrefix($name . 'NamingStrategy'))
			->setClass("DoctrineModule\Mapping\VenneNamingStrategy")
			->setInternal(TRUE);


		$container->addDefinition($this->configurationsPrefix($name))
			->setClass("Doctrine\ORM\Configuration")
			->addSetup('setMetadataCacheImpl', '@' . $this->prefix("cache"))
			->addSetup("setQueryCacheImpl", '@' . $this->prefix("cache"))
			->addSetup("setMetadataDriverImpl", $this->configurationsPrefix('@' . $name . ucfirst($config['mappingDriver']) . 'Driver'))
			->addSetup("setProxyDir", $config['proxiesDir'])
			->addSetup("setProxyNamespace", $config['proxiesNamespace'])
			->addSetup('setNamingStrategy', $this->configurationsPrefix('@' . $name . "NamingStrategy"))
			->setInternal(TRUE);

		if ($container->parameters["debugMode"]) {
			$container->getDefinition($this->configurationsPrefix($name))
				->addSetup("setAutoGenerateProxyClasses", TRUE);
		}
	}


	protected function processConsole()
	{
		$container = $this->getContainerBuilder();

		$container->addDefinition($this->prefix('consoleCommandDBALRunSql'))
			->setClass('Doctrine\DBAL\Tools\Console\Command\RunSqlCommand')
			->addTag('commnad')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandDBALImport'))
			->setClass('Doctrine\DBAL\Tools\Console\Command\ImportCommand')
			->addTag('command')
			->setAutowired(FALSE);

		// console commands - ORM
		$container->addDefinition($this->prefix('consoleCommandORMCreate'))
			->setClass('Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandORMUpdate'))
			->setClass('Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandORMDrop'))
			->setClass('Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandORMGenerateProxies'))
			->setClass('Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandORMRunDql'))
			->setClass('Doctrine\ORM\Tools\Console\Command\RunDqlCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandORMConvertMapping'))
			->setClass('Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandValidateSchema'))
			->setClass('Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand')
			->addTag('command')
			->setAutowired(FALSE);

		// console commands - DBAL
		$container->addDefinition($this->prefix('consoleCommandDBALDiff'))
			->setClass('Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandDBALExecute'))
			->setClass('Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandDBALGenerate'))
			->setClass('Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandDBALMigrate'))
			->setClass('Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandDBALStatus'))
			->setClass('Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand')
			->addTag('command')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('consoleCommandDBALVersion'))
			->setClass('Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand')
			->addTag('command')
			->setAutowired(FALSE);

		// Helpers
		$container->addDefinition($this->prefix('entityManagerHelper'))
			->setClass('Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper', array('@entityManager'))
			->addTag('commandHelper', 'em')
			->setAutowired(FALSE);
		$container->addDefinition($this->prefix('connectionHelper'))
			->setClass('Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper', array('@' . $this->connectionsPrefix('default')))
			->addTag('commandHelper', 'db')
			->setAutowired(FALSE);
	}


	protected function processEventManager($name, array $config)
	{
		$container = $this->getContainerBuilder();

		$container->addDefinition($this->eventManagersPrefix($name))
			->setClass("Doctrine\Common\EventManager");
	}


	protected function processSchemaManager($name, array $config)
	{
		$container = $this->getContainerBuilder();

		$container->addDefinition($this->schemaManagersPrefix($name))
			->setClass("Doctrine\DBAL\Schema\AbstractSchemaManager")
			->setFactory($this->connectionsPrefix('@' . $config['connection']) . "::getSchemaManager");
	}


	public function processEntityManager($name, array $config)
	{
		$container = $this->getContainerBuilder();

		$container->addDefinition($this->entityManagersPrefix($name))
			->setClass("Doctrine\ORM\EntityManager")
			->setFactory("\Doctrine\ORM\EntityManager::create", array(
					$this->connectionsPrefix('@' . $config['connection']),
					$this->configurationsPrefix('@' . $name),
					$this->eventManagersPrefix('@' . $name)
				)
			);
	}


	public function processConnection($name, array $config)
	{
		$container = $this->getContainerBuilder();

		$container->addDefinition($this->connectionsPrefix($name))
			->setClass('Doctrine\DBAL\Connection')
			->setFactory('Doctrine\DBAL\DriverManager::getConnection', array($config, $config['eventManager']))
			->addSetup('$panel = new DoctrineModule\Diagnostics\ConnectionPanel; $panel->setConnection($service); $service->getConfiguration()->setSQLLogger($panel); Nette\Diagnostics\Debugger::$bar->addPanel($panel); ? ', array(''));
	}


	/**
	 * @param string
	 * @return string
	 */
	protected function connectionsPrefix($id)
	{
		$name = Strings::startsWith($id, '@') ?
			('@' . static::CONNECTIONS_PREFIX . '.' . substr($id, 1)) : (static::CONNECTIONS_PREFIX . '.' . $id);
		return $this->prefix($name);
	}


	/**
	 * @param string
	 * @return string
	 */
	protected function entityManagersPrefix($id)
	{
		$name = Strings::startsWith($id, '@') ?
			('@' . static::ENTITY_MANAGERS_PREFIX . '.' . substr($id, 1)) : (static::ENTITY_MANAGERS_PREFIX . '.' . $id);
		return $this->prefix($name);
	}


	/**
	 * @param string
	 * @return string
	 */
	protected function eventManagersPrefix($id)
	{
		$name = Strings::startsWith($id, '@') ?
			('@' . static::EVENT_MANAGERS_PREFIX . '.' . substr($id, 1)) : (static::EVENT_MANAGERS_PREFIX . '.' . $id);
		return $this->prefix($name);
	}


	/**
	 * @param string
	 * @return string
	 */
	protected function schemaManagersPrefix($id)
	{
		$name = Strings::startsWith($id, '@') ?
			('@' . static::SCHEMA_MANAGERS_PREFIX . '.' . substr($id, 1)) : (static::SCHEMA_MANAGERS_PREFIX . '.' . $id);
		return $this->prefix($name);
	}


	/**
	 * @param string
	 * @return string
	 */
	protected function configurationsPrefix($id)
	{
		$name = Strings::startsWith($id, '@') ?
			('@' . static::CONFIGURATIONS_PREFIX . '.' . substr($id, 1)) : (static::CONFIGURATIONS_PREFIX . '.' . $id);
		return $this->prefix($name);
	}


	public static function checkConnection(\Nette\DI\Container $context, \Doctrine\ORM\EntityManager $entityManager)
	{
		if (self::$isConnected === NULL) {
			$ret = (object)array('val' => TRUE);
			$connection = $entityManager->getConnection();
			$old = set_error_handler(function () use ($ret) {
				$ret->val = FALSE;
			});

			try {
				$c = $connection->connect();
				if (!is_bool($c)) {
					$ret->val = FALSE;
				}
				$connection->getSchemaManager()->tablesExist('user'); // try connect with some sql
			} catch (\Exception $ex) {
				$ret->val = FALSE;
			}

			set_error_handler($old);
			self::$isConnected = $ret->val;
		}

		return self::$isConnected;
	}


	public function beforeCompile()
	{
		$this->registerListeners();
	}


	protected function registerListeners()
	{
		$container = $this->getContainerBuilder();
		$evm = $container->getDefinition('doctrine.eventManagers.default');
		$em = $container->getDefinition('doctrine.entityManagers.default');

		foreach ($this->getSortedServices("listener") as $item) {
			$class = $container->getDefinition($item)->class;

			if (is_subclass_of($class, 'Doctrine\Common\EventSubscriber')) {
				$evm->addSetup("addEventSubscriber", "@{$item}");
			} else {
				$em->addSetup('$service->getConfiguration()->getEntityListenerResolver()->register(?)', "@{$item}");
			}
		}
	}


	public static function getCaches()
	{
		return self::$caches;
	}
}

