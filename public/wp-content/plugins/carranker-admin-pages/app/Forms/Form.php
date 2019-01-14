<?php

declare(strict_types=1);

namespace CarrankerAdmin\App\Forms;

abstract class Form
{
    public $hasContentField = false;
    public $textFields = [];
    public $integerFields = [];
    public $floatFields = [];
    public $contentField = '';
    public $hiddenFields = ['id' => 0, 'carrankerAdminAction' => 'create'];
    public $selectFields = [];
    public $selectChoices = [];
    public $errors = [];

    public function __construct(string $createOrUpdate, object $request = null)
    {
        if (isset($request)) {
            foreach ($this->textFields as $key => $field) {
                $this->textFields[$key] = $request->$key ?? $this->textFields[$key];
            }

            foreach ($this->integerFields as $key => $field) {
                $this->integerFields[$key] = $request->$key ?? $this->integerFields[$key];
            }

            foreach ($this->floatFields as $key => $field) {
                $this->floatFields[$key] = $request->$key ?? $this->floatFields[$key];
            }

            foreach ($this->selectFields as $key => $field) {
                $this->selectFields[$key] = $request->$key ?? $this->selectFields[$key];
            }

            $this->hiddenFields['id'] = $request->id ?? $this->hiddenFields['id'];

            if ($this->hasContentField) {
                $content = $request->content;
                if (isset($content)) {
                    $this->contentField = mb_convert_encoding($content, 'ISO-8859-1', 'HTML-ENTITIES');
                    $this->contentField = iconv("UTF-8", "UTF-8//IGNORE", $this->contentField);
                }
            }

            $this->hiddenFields['carrankerAdminAction'] = $createOrUpdate;
        }
    }

    public function validate(object $request)
    {
        foreach ($this->rules() as $keyRule => $rule) {
            $ruleArray = explode('|', $rule);
            if ($ruleArray[1] !== 'nullable' && $ruleArray[1] !== 'required') {
                $this->errors[$keyRule] = 'Second parameter in rule ' . $keyRule . ' must be either required or nullable.';
                return false;
            }
            if ($ruleArray[1] === 'required' && ($request->$keyRule === '' || is_null($request->$keyRule))) {
                $this->errors[$keyRule] = "Required field " . $keyRule . " is empty.";
                return false;
            }
            if ($ruleArray[0] === 'string' && !is_string($request->$keyRule)) {
                $this->errors[$keyRule] = $keyRule . " is not of type string.";
            }
            if ($ruleArray[0] === 'integer' && false === filter_var($request->$keyRule, FILTER_VALIDATE_INT)) {
                $this->errors[$keyRule] = $keyRule . " is not of type integer.";
            }
            if ($ruleArray[0] === 'float' && false === filter_var($request->$keyRule, FILTER_VALIDATE_FLOAT)) {
                $this->errors[$keyRule] = $keyRule . " is not of type float.";
            }
            if ($ruleArray[0] === 'bool' && false === filter_var($request->$keyRule, FILTER_VALIDATE_BOOLEAN)) {
                $this->errors[$keyRule] = $keyRule . " is not of type boolean.";
            }
            if ($ruleArray[0] === 'object' && !is_object($request->$keyRule)) {
                $this->errors[$keyRule] = $keyRule . " is not of type object.";
            }
            if ($ruleArray[0] === 'array' && !is_array($request->$keyRule)) {
                $this->errors[$keyRule] = $keyRule . " is not of type array.";
            }
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }
}