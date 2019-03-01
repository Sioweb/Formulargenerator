<?php

namespace Sioweb\Lib\Formgenerator\Core;

class Fieldset
{
    
    public $template = 'fieldset';

    public function __construct($Name, Array $Fieldset)
    {
        $this->id = $Name;
        $this->assign($Fieldset);
        $this->fields = array_flip($this->fields);
    }

    protected function assign($data)
    {
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function updateField($field)
    {
        $this->fields[$field->name] = $field;
    }

    public function getFields()
    {
        return array_flip($this->fields);
    }
}