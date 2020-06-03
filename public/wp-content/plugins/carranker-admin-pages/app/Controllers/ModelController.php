<?php

declare(strict_types=1);

namespace CarrankerAdmin\App;

use CarrankerAdmin\App\Forms\ModelForm;
use CarrankerAdmin\App\Models\ElasticJob;
use CarrankerAdmin\App\Models\Model;
use CarrankerAdmin\App\Models\Make;

class ModelController extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        require_once dirname(__DIR__) . '/Forms/ModelForm.php';
        require_once dirname(__DIR__) . '/Models/ElasticJob.php';
    }

    public function view(array $urlParams)
    {
        $createOrUpdate = 'create';
        $formObject = null;
        if (!empty($urlParams['modelname'])) {
            $names = explode(';', $urlParams['modelname']);
            $model = Model::getByNames($names[0], $names[1]);
            $this->set('model', $model);
            $formObject = $model->getProperties();
            $createOrUpdate = 'update';
        }
        $this->set('form', new ModelForm($createOrUpdate, $formObject));
    }

    public function create(array $urlParams, object $request)
    {
        $form = new ModelForm('create', $request);

        if ($form->validate($request)) {
            $model = new Model($request);
            $make = Make::findByName($request->make);
            $model->setContent($request->content);
            $model->setMakeId($make->getId());
            $model->create();
            $request->id = $model->getId();
            $this->set('model', $model);
            $form = new ModelForm('update', $request);
            $object= new \stdClass();
            $object->model_id = $model->getId();
            $object->action = 'create';
            $job = new ElasticJob($object);
            $job->create();
        }
        $this->set('form', $form);
        $this->_template->_action = 'view';
    }

    public function update(array $urlParams, object $request)
    {
        $form = new ModelForm('update', $request);

        if ($form->validate($request)) {
            $model = new Model($request);
            $make = Make::findByName($model->getMake());
            $model->setContent($request->content);
            $model->setMakeId($make->getId());
            $model->update();
            $this->set('model', $model);
            $object= new \stdClass();
            $object->model_id = $model->getId();
            $object->action = 'update';
            $job = new ElasticJob($object);
            $job->create();
        }
        $this->set('form', $form);
        $this->_template->_action = 'view';
    }

    public function delete(array $urlParams, object $request)
    {
        Model::delete((int) $request->deleteModelId);
        $this->set('form', new ModelForm('create'));
        $this->_template->_action = 'view';
        $object= new \stdClass();
        $object->model_id = (int) $request->deleteModelId;
        $object->action = 'delete';
        $job = new ElasticJob($object);
        $job->create();
    }
}