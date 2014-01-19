<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TranslatorModule\Commands;

use Venne;
use TranslatorModule\Extraction\Extractor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ExtractCommand extends Command
{

	/** @var Extractor */
	protected $extractor;


	/**
	 * @param Extractor $extractor
	 */
	public function __construct(Extractor $extractor)
	{
		parent::__construct();

		$this->extractor = $extractor;
	}


	/**
	 * @see Console\Command\Command
	 */
	protected function configure()
	{
		$this
			->setName('translator:extract')
			->setDescription('Extract strings from path.')
			->addArgument('path', InputArgument::REQUIRED, 'path for extract')
			->addArgument('file', InputArgument::OPTIONAL, 'file for save');
	}


	/**
	 * @see Console\Command\Command
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$path = $input->getArgument('path');
		$file = $input->getArgument('file');

		$path = substr($path, 0, 1) == '/' ? $path : getcwd() . '/' . $path;
		$results = $this->extractor->extract($path);

		if (!$file) {
			$output->write(print_r($results));
			return;
		}

		$file = substr($file, 0, 1) == '/' ? $file : getcwd() . '/' . $file;

		// optimize data
		$results = array_flip($results);
		foreach ($results as $key => $item) {
			$results[$key] = '';
		}

		// Validate
		$filename = substr($file, strrpos($file, '/') + 1);
		if (substr_count($filename, '.') !== 2) {
			throw new \Nette\InvalidArgumentException("Filename must contain two points. For example 'admin.en.neon'");
		}
		$type = substr($filename, strrpos($filename, '.') + 1);
		$class = "\\TranslatorModule\\Drivers\\" . ucfirst($type) . "Driver";
		if (!class_exists($class)) {
			throw new \Nette\InvalidArgumentException("Driver '$type' does not exists");
		}

		// Save
		/** @var $driver \TranslatorModule\Drivers\IDriver */
		$driver = new $class($file);
		$origData = $driver->load();
		$results = array_merge($results, $origData);
		ksort($results);
		$driver->save($results);

		$output->writeln('Done');
	}
}
