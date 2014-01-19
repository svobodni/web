<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace BlogModule\Pages\Blog\SliderElement;

use BlogModule\Pages\Blog\BlogElement\BlogElement;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class SliderElement extends BlogElement
{

	public function renderDefault()
	{
		$this->template->width = $this->getExtendedElement()->width;
		$this->template->height = $this->getExtendedElement()->height;
	}

}
