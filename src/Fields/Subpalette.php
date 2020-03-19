<?php

namespace Sioweb\Lib\Formgenerator\Fields;

class Subpalette
{
    public $template = 'subpalette';
    public $label = false;

    public function __construct($Name, Array $Subpalette)
    {
        $this->id = $Name;
        $this->assign($Subpalette);
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