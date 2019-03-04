<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Core\Form;

class Button extends Field
{
    public function __construct($fieldId, array $FieldConfig, Form $Form)
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
