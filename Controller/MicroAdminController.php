<?php

namespace Elao\Bundle\MicroAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

abstract class MicroAdminController extends Controller
{
    /**
     * Config
     *
     * @var Elao\Bundle\MicroAdminBundle\Behaviour\ConfigInterface
     */
    protected $config;

    /**
     * Model manager
     *
     * @var Elao\Bundle\MicroAdminBundle\Behaviour\ModelManagerInterface
     */
    protected $modelManager;

    /**
     * List all models
     *
     * @param Request $request
     *
     * @return array
     */
    protected function list(Request $request)
    {
        $models = $this->modelManager->findAll();

        return ['models' => $models];
    }

    /**
     * Show a model
     *
     * @param Request $request
     *
     * @return array
     */
    protected function show(Request $request, $model)
    {
        return ['model' => $model];
    }

    /**
     * Create a new model
     *
     * @param Request $request
     *
     * @return array
     */
    protected function new(Request $request)
    {
        $model = $this->modelManager->getInstance();

        return $this->form($model, 'new');
    }

    /**
     * Edit an existing model
     *
     * @param Request $request
     * @param mixed $model
     *
     * @return array
     */
    protected function edit(Request $request, $model)
    {
        return $this->form($model, 'edit');
    }

    /**
     * Generate a form for the given model
     *
     * @param Request $request
     * @param mixed $model
     *
     * @return array
     */
    protected function form(Request $request, $model, $action)
    {
        $form = $this->createForm($this->config->getFormType($action),  $model);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->modelManager->persist($model)->flush();

                return $this->redirect($this->getRedirection($model, $action));
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * Delete
     *
     * @param Request $request
     * @param mixed $model
     *
     * @return array
     */
    public function delete(Request $request, $model)
    {
        $form = $this->createForm('micro_admin_delete', $model);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->modelManager->delete($model)->flush();

                return $this->redirect($this->redirection);
            }
        }

        return ['form' => $form->createView()];
    }
}
