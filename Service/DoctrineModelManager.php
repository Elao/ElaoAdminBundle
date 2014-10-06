<?php

namespace Elao\Bundle\MicroAdminBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Elao\Bundle\MicroAdminBundle\Behaviour\ModelManagerInterface;

/**
 * Doctrine Model Manager
 */
class DoctrineModelManager implements ModelManagerInterface
{
    /**
     * Object manager
     *
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Constructor
     *
     * @param ObjectManager $objectManager
     * @param string $className
     */
    public function __construct(ObjectManager $objectManager, $className)
    {
        $this->objectManager = $objectManager;
        $this->className     = $className;
    }

    /**
     * Find a model
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function find(array $parameters = [])
    {
        return $this->getRepository()->findOneBy($parameters);
    }

    /**
     * Find all models
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function findAll(array $parameters = [])
    {
        return $this->getRepository()->findBy($parameters);
    }

    /**
     * Get repository
     *
     * @return Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->objectManager->getRepository($this->className);
    }

    /**
     * {@inheritdoc}
     */
    public function getInstance()
    {
        return new $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($model)
    {
        $this->objectManager->persist($model);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($model)
    {
        $this->objectManager->delete($model);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->objectManager->flush();

        return $this;
    }
}