<?php

declare(strict_types=1);

namespace CarrankerAdmin\App;

use CarrankerAdmin\App\Forms\ProfanityForm;
use CarrankerAdmin\App\Models\Profanity;

class ProfanityController extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        require_once dirname(__DIR__) . '/Models/Profanity.php';
        require_once dirname(__DIR__) . '/Forms/ProfanityForm.php';
    }

    public function view(array $urlParams)
    {
        $formObject = null;
        if (isset($urlParams['character'])) {
            $this->set('profanities', Profanity::all($urlParams['character']));
        } else {
            $this->set('profanities', Profanity::all('a'));
        }

        $this->set('form', new ProfanityForm('create', $formObject));
    }

    public function create(array $urlParams, object $request)
    {
        $form = new ProfanityForm('create');

        if ($form->validate($request)) {
            $profanity = new Profanity($request);
            $profanity->create();
            $this->set('profanities', Profanity::all(substr($request->name, 0, 1)));
        } else {
            $this->set('profanities', Profanity::all($urlParams['character']));
        }


        $this->set('form', $form);
        $this->_template->_action = 'view';
    }

    public function update(array $urlParams, $request)
    {
        $form = new ProfanityForm('update', $request);

        if ($form->validate($request)) {
            $profanity = new Profanity($request);
            $profanity->update();
            $this->set('profanities', Profanity::all(substr($request->name, 0, 1)));
        } else {
            $this->set('profanities', Profanity::all($urlParams['character']));
        }

        $this->set('form', $form);
        $this->_template->_action = 'view';
    }

    public function delete(array $urlParams, $request)
    {
        $profanity = Profanity::findByName($request->deleteProfanityName);
        if (!is_null($profanity)) {
            Profanity::delete($profanity->getId());
            $this->set('profanities', Profanity::all(substr($request->deleteProfanityName, 0, 1)));
        } else {
            $this->set('profanities', Profanity::all($urlParams['character']));
        }
        $this->set('form', new ProfanityForm('create'));
        $this->_template->_action = 'view';
    }
}