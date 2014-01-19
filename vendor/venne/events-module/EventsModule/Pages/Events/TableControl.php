<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace EventsModule\Pages\Events;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class TableControl extends AbstractTableControl
{

	/** @var RouteRepository */
	protected $routeRepository;

	/** @var EventFormFactory */
	protected $formFactory;


	/**
	 * @param RouteRepository $routeRepository
	 * @param EventFormFactory $formFactory
	 */
	public function __construct(RouteRepository $routeRepository, EventFormFactory $formFactory)
	{
		parent::__construct();

		$this->routeRepository = $routeRepository;
		$this->formFactory = $formFactory;
	}


	protected function getRepository()
	{
		return $this->routeRepository;
	}


	protected function getFormFactory()
	{
		return $this->formFactory;
	}
}
