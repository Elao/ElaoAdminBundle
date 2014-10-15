<?php

namespace  Elao\Bundle\AdminBundle\Factory;

use Elao\Bundle\AdminBundle\Behaviour\AdministrationInterface;
use Elao\Bundle\AdminBundle\Service\Administration;

/**
 * Administration Factory
 */
class AdministrationFactory
{
    /**
     * Administrations
     *
     * @var array
     */
    protected $administrations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->administrations = [];
    }

    /**
     * Create administration
     *
     * @param string $name
     * @param array $options
     *
     * @return AdministrationInterface
     */
    public function create($name, $options)
    {
        if (array_key_exists($name, $this->administrations)) {
            throw new Exception(sprintf('Administration "%s" already exists', $name));
        }

        $administration = new Administration;

        $administration->setName($name);
        $administration->setModel($options['model']);
        $administration->setController($options['controller']);
        $administration->setManager();
        //$administration->setFormTypes($options['form']);

        $this->add($administration);

        return $administration;
    }

    /**
     * Add administation
     *
     * @param string $name Name
     *
     * @param AdministrationInterface $administation
     */
    public function add(AdministrationInterface $administation)
    {
        $this->administations[$administation->getName()] = $administation;
    }

    /**
     * Get a administation
     *
     * @param string $name Name
     *
     * @return AdministrationInterface
     */
    public function get($name)
    {
        return $this->administations[$name];
    }

    public function getAll()
    {
        return $this->administations;
    }
}