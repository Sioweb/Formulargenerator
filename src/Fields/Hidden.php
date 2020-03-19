<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Core\Form;

class Hidden extends Field
{
    public function __construct($fieldId, Array $FieldConfig, Form $Form)
    {
        parent::__construct($fieldId, $FieldConfig, $Form);
        if (empty($FieldConfig['forceLabel'])) {
            $this->label = false;
        }
    }

    protected function updateValue()
    {
        return;
    }
}
