<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CmsModule\Content\Listeners;

use CmsModule\Content\Entities\RouteAliasEntity;
use CmsModule\Content\Entities\RouteEntity;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class ExtendedRouteListener
{

	/** @var boolean */
	private $inPreFlush = false;

	/** @ORM\PostLoad */
	public function postLoadHandler(RouteEntity $route, LifecycleEventArgs $event)
	{
		$em = $event->getEntityManager();
		$route->setExtendedRouteCallback(function () use ($em, $route) {
			return $em->getRepository($route->getClass())->findOneBy(array('route' => $route->id));
		});
	}

	/** @ORM\PreFlush */
	public function preFlushHandler(RouteEntity $route, PreFlushEventArgs $event)
	{
		if ($this->inPreFlush) {
			return;
		}

		$this->inPreFlush = true;

		$em = $event->getEntityManager();
		$uow = $em->getUnitOfWork();
		foreach ($uow->getScheduledEntityUpdates() as $entity) {
			if ($entity instanceof RouteEntity) {
				$changeSet = $uow->getEntityChangeSet($entity);

				if (isset($changeSet['url'])) {
					$routeAliasEntity = new RouteAliasEntity;
					$routeAliasEntity->setRoute($entity);
					$routeAliasEntity->setAliasUrl($changeSet['url'][0]);
					$routeAliasEntity->setAliasLang($entity->getLanguage() !== null
						? $entity->getLanguage()->getAlias()
						: null
					);
					$routeAliasEntity->setAliasDomain($entity->getDomain() !== null
						? $entity->getDomain()->getDomain()
						: null
					);
					$em->persist($routeAliasEntity);
					$em->flush($routeAliasEntity);

					$meta = $em->getClassMetadata(get_class($routeAliasEntity));
					$uow->recomputeSingleEntityChangeSet($meta, $routeAliasEntity);
				}
			}
		}

		$this->inPreFlush = false;
	}

}
