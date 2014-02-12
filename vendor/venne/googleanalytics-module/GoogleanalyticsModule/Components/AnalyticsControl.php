<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace GoogleanalyticsModule\Components;

use CmsModule\Content\Control;
use Nette\Http\Request;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class AnalyticsControl extends Control
{


	/** @var Request */
	private $httpRequest;


	/**
	 * @param Request $httpRequest
	 */
	public function __construct(Request $httpRequest)
	{
		parent::__construct();

		$this->httpRequest = $httpRequest;
	}


	public function render($accountId)
	{
		echo '<script>
  (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');

  ga(\'create\', \'' . $accountId . '\', \'' . $this->httpRequest->url->host . '\');
  ga(\'send\', \'pageview\');

</script>
';
	}

}
