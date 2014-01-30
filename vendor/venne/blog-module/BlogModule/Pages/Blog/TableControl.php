<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace BlogModule\Pages\Blog;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class TableControl extends AbstractTableControl
{

	/** @var ArticleRepository */
	protected $articleRepository;

	/** @var BlogFormFactory */
	protected $formFactory;


	/**
	 * @param ArticleRepository $articleRepository
	 * @param BlogFormFactory $formFactory
	 */
	public function __construct(ArticleRepository $articleRepository, BlogFormFactory $formFactory)
	{
		parent::__construct();

		$this->articleRepository = $articleRepository;
		$this->formFactory = $formFactory;
	}


	protected function getRepository()
	{
		return $this->articleRepository;
	}


	protected function getFormFactory()
	{
		return $this->formFactory;
	}
}
