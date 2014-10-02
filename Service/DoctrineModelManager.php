<?php

namespace Elao\Bundle\MicroAdminBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Doctrine Model Manager
 */
class DoctrineModelManager implements ModelManagerInterface
{
    /**
     * Admin configuration
     *
     * @var AdminConfig
     */
    protected $config;

    /**
     * Object manager
     *
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Get a new instance of the model
     *
     * @return mixed
     */
    public function getInstance()
    {
        return new ($this->config->getClassname());
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
        return $this->objectManager->getRepository($this->config->getClassname());
    }

    /**
     * Persist model
     *
     * @param mixed $model
     *
     * @return ModelManagerInterface
     */
    public function persist($model)
    {
        $this->objectManager->persist($model);

        return $this;
    }

    /**
     * Delete model
     *
     * @param mixed $model
     *
     * @return ModelManagerInterface
     */
    public function delete($model)
    {
        $this->objectManager->delete($model);

        return $this;
    }

    /**
     * Flush
     *
     * @return ModelManagerInterface
     */
    public function flush()
    {
        $this->objectManager->flush();

        return $this;
    }
}