<?php

namespace Elao\Bundle\MicroAdminBundle\Behaviour;

interface ConfigInterface
{
    /**
     * Get model classname
     *
     * @return string
     */
    public function getClassname();

    /**
     * Get formtype for the given action
     *
     * @param string $action
     *
     * @return string|Symfony\Component\Form\AbstractType
     */
    public function getFormType($action);

    /**
     * Get the success redirection url for the given model and action
     *
     * @param mixed $model
     * @param string $action
     *
     * @return mixed
     */
    public function getRedirection($model, $action);
}