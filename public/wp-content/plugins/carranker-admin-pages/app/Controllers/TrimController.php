<?php

declare(strict_types=1);

namespace CarrankerAdmin\App;

use CarrankerAdmin\App\Models\Model;
use CarrankerAdmin\App\Models\Trim;
use CarrankerAdmin\App\Forms\TrimForm;

class TrimController extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        require_once dirname(__DIR__) . '/Models/Trim.php';
        require_once dirname(__DIR__) . '/Forms/TrimForm.php';
    }

    public function view(array $urlParams)
    {
        $formObject = null;
        $createOrUpdate = 'create';
        if (isset($urlParams['modelname'])) {
            $names = explode(';', $urlParams['modelname']);
            $model = Model::getByNames($names[0], $names[1]);
            if (isset($urlParams['serieTrimId']) && !empty($urlParams['serieTrimId'])) {
                $trimId = $urlParams['serieTrimId'];
            }
            if (isset($urlParams['trimTypeId']) && !empty($urlParams['trimTypeId'])) {
                $trimId = $urlParams['trimTypeId'];
            }
            if (isset($trimId)) {
                $trim = Trim::getById((int) $trimId);
                $this->set('trim', $trim);
                $formObject = $trim->getProperties();
                $this->set('generationTrim', $trim->getYearBegin() . "-" . $trim->getYearEnd());
                $createOrUpdate = 'update';
            } else {
                $formObject = new \stdClass();
                $formObject->make = $model->getMake();
                $formObject->model = $model->getName();
            }
            $this->decorateView($model);
        }
        $this->set('form', new TrimForm($createOrUpdate, $formObject));
    }

    public function create(array $urlParams, object $request)
    {
        $form = new TrimForm('create', $request);

        if ($form->validate($request)) {
            $trim = new Trim($request);
            $modelname = explode(';', $trim->getModel());
            $model = Model::getByNames($trim->getMake(), $modelname[1]);
            $trim->setModelId((int)$model->getId());
            $trim->create();
            $request->id = $trim->getId();
            $this->set('trim', $trim);
            $this->set('generationTrim', $trim->getYearBegin() . "-" . $trim->getYearEnd());
            $this->decorateView($model);
            $form = new TrimForm('update', $request);
        }
        $this->set('form', $form);

        $this->_template->_action = 'view';
    }

    public function update(array $urlParams, object $request)
    {
        $form = new TrimForm('update', $request);

        if ($form->validate($request)) {
            $trim = new Trim($request);
            $modelname = explode(';', $trim->getModel());
            $model = Model::getByNames($trim->getMake(), $modelname[1]);
            $trim->setModelId((int)$model->getId());
            $trim->update();
            $this->set('trim', $trim);
            $this->decorateView($model);
        }
        $this->set('form', $form);
        $this->set('hasTrimTypes', Trim::$hasTrimTypes);
        $this->_template->_action = 'view';
    }

    public function delete(array $urlParams, object $request)
    {
        $trim = Trim::getById((int) $request->deleteTrimId);
        $modelname = explode(';', $trim->getModel());
        $model = Model::getByNames($trim->getMake(), $modelname[1]);
        Trim::delete((int) $request->deleteTrimId);
        $this->set('form', new TrimForm('create'));
        $this->decorateView($model);
        $this->_template->_action = 'view';
    }

    private function decorateView(Model $model)
    {
        $this->set('model', $model);
        $this->set('generationsSeriesTrims', Trim::getGenerationsSeriesTrims($model));
        $this->set('hasTrimTypes', Trim::$hasTrimTypes);
    }
}