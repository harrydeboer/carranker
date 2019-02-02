<?php

declare(strict_types=1);

namespace CarrankerAdmin\App;

use CarrankerAdmin\App\Forms\MakeForm;
use CarrankerAdmin\App\Models\Make;

class MakeController extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        require_once dirname(__DIR__) . '/Forms/MakeForm.php';
    }

    public function view(array $urlParams)
    {
        $createOrUpdate = 'create';
        $formObject = null;
        if (!empty($urlParams['makename'])) {
            $make = Make::findByName($urlParams['makename']);
            $this->set('make', $make);
            $formObject = $make->getProperties();
            $createOrUpdate = 'update';
        }
        $this->set('form', new MakeForm($createOrUpdate, $formObject));
    }

    public function create(array $urlParams, object $request)
    {
        $form = new MakeForm('create', $request);

        if ($form->validate($request)) {
            $make = new Make($request);
            $make->setContent($request->content);
            $make->create();
            $request->id = $make->getId();
            $this->set('make', $make);
            $form = new MakeForm('update', $request);
        }
        $this->set('form', $form);
        $this->_template->_action = 'view';
    }

    public function update(array $urlParams, object $request)
    {
        $form = new MakeForm('update', $request);

        if ($form->validate($request)) {
            $make = new Make($request);
            $make->setContent($request->content);
            $make->update();
            $this->set('make', $make);
        }
        $this->set('form', $form);
        $this->_template->_action = 'view';
    }

    public function delete(array $urlParams, object $request)
    {
        Make::delete((int) $request->deleteMakeId);
        $this->set('form', new MakeForm('create'));
        $this->_template->_action = 'view';
    }
}