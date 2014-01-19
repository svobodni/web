<?php

namespace GalleryModule\Pages\Gallery;

use CmsModule\Content\Entities\ExtendedRouteEntity;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
abstract class AbstractItemEntity extends ExtendedRouteEntity
{

	/**
	 * @var CategoryEntity
	 * @ORM\ManyToOne(targetEntity="CategoryEntity", inversedBy="items")
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $category;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $position;


	protected function startup()
	{
		parent::startup();

		$this->position = time();
		$this->route->setLocalUrl(Strings::webalize(Strings::random()));
		$this->getRoute()->setPublished(TRUE);
	}


	/**
	 * @param $category
	 */
	public function setCategory($category)
	{
		$this->category = $category;
	}


	/**
	 * @return CategoryEntity
	 */
	public function getCategory()
	{
		return $this->category;
	}


	/**
	 * @param int $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}


	/**
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}
}
