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

use Doctrine\Common\Persistence\ObjectManager;
use Elao\Bundle\AdminBundle\Behaviour\ModelManagerInterface;

/**
 * Doctrine Model Manager
 */
class DoctrineModelManager implements ModelManagerInterface
{
    /**
     * The default Doctrine object manager
     *
     * @var \Doctrine\Common\Peristence\ObjectManager
     */
    protected $objectManager;

    /**
     * Constructor
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param string                                     $className
     */
    public function __construct(ObjectManager $objectManager, $className)
    {
        $this->objectManager = $objectManager;
        $this->className     = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function find(array $parameters = [])
    {
        return $this->getRepository()->findOneBy($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(array $parameters = [])
    {
        return $this->getRepository()->findBy($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget(array $parameters = [])
    {
        $alias        = strtolower(substr($this->className, strrpos($this->className, '\\')+1));
        $queryBuilder = $this->getRepository()->createQueryBuilder($alias);

        foreach ($parameters as $attribute => $value) {
            $property = sprintf('%s.%s', $queryBuilder->getRootAlias(), $attribute);

            if ($value !== null) {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($property, $attribute))
                    ->setParameter($attribute, $value);
            } else {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->isNull($property));
            }
        }

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return new $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($model)
    {
        $this->objectManager->persist($model);
        $this->flush();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($model)
    {
        $this->objectManager->remove($model);
        $this->flush();

        return $this;
    }

    /**
     * Get classname
     *
     * @return string
     */
    public function getClassname()
    {
        return $this->className;
    }

    /**
     * Get repository
     *
     * @return Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->objectManager->getRepository($this->className);
    }

    /**
     * Flush changes
     *
     * @return ModelManagerInterface
     */
    protected function flush()
    {
        $this->objectManager->flush();

        return $this;
    }
}
