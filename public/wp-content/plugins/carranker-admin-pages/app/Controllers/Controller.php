<?php

declare(strict_types=1);

namespace CarrankerAdmin\App;

use CarrankerAdmin\App\Models\Make;
use CarrankerAdmin\App\Models\Model;
use CarrankerAdmin\Library\Template;

abstract class Controller
{
    protected $_controller;
    protected $_action;
    protected $_template;

    /* Builds a model, user and template object. Parameters, brands */
    public function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;

        require_once dirname(__DIR__, 2) . '/library/Template.php';
        require_once dirname(__DIR__) . '/Models/BaseModel.php';
        require_once dirname(__DIR__) . '/Models/Make.php';
        require_once dirname(__DIR__) . '/Models/Model.php';
        require_once dirname(__DIR__) . '/Forms/Form.php';

        $this->_template = new Template($controller, $action);
    }

    protected function set($name, $value) {
        $this->_template->set($name, $value);
    }

    public function render()
    {
        $this->set('makenames', Make::getMakenames());
        $this->set('modelnames', Model::getModelnames());
        $this->_template->render();
    }
}