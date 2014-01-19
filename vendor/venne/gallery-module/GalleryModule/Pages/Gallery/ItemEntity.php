<?php

namespace GalleryModule\Pages\Gallery;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 * @ORM\Entity(repositoryClass="\GalleryModule\Pages\Gallery\ItemRepository")
 * @ORM\Table(name="gallery_photo")
 */
class ItemEntity extends AbstractItemEntity
{

}
