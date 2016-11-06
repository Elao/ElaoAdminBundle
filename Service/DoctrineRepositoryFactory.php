<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\DefaultRepositoryFactory;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class DoctrineRepositoryFactory implements RepositoryFactory, ContainerAwareInterface
{
    /**
     * Default repository factory
     *
     * @var RepositoryFactory
     */
    private $defaultFactory;

    /**
     * Repositories
     *
     * @var array
     */
    private $repositories = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->defaultFactory = new DefaultRepositoryFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Register a Repository as a service
     *
     * @param string $entityClassName
     * @param string $serviceId
     */
    public function registerServiceRepository($entityClassName, $serviceId)
    {
        $this->repositories[$entityClassName] = $serviceId;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $entityClassName = $entityManager->getClassMetadata($entityName)->getName();

        if ($entityClassName && isset($this->repositories[$entityClassName])) {
            $serviceId = $this->repositories[$entityClassName];

            return $this->container->get($serviceId);
        }

        return $this->createRepository($entityManager, $entityName);
    }

    /**
     * Create repository with default factory
     *
     * @param EntityManagerInterface $entityManager
     * @param string $entityName
     *
     * @return EntityRepository
     */
    public function createRepository(EntityManagerInterface $entityManager, $entityName)
    {
        return $this->defaultFactory->getRepository($entityManager, $entityName);
    }
}
