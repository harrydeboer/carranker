<?php

declare(strict_types=1);

namespace CarrankerAdmin\Library;

class Template
{
    protected $_variables = array();
    protected $_controller;
    public $_action;

    public function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    /* Set variables. */
    public function set($name, $value)
    {
        $this->_variables[$name] = $value;
    }

    /* Display template. */
    public function render() {
        extract($this->_variables);

        require_once dirname(__DIR__) . '/app/views/header.php';
        require_once dirname(__DIR__) . '/app/views/' . $this->_controller . '/' . $this->_action . '.php';
        require_once dirname(__DIR__) . '/app/views/footer.php';
    }
}

