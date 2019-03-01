<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Core\Form;

class Select extends Field
{
    public function __construct($fieldId, Array $FieldConfig, Form $Form)
    {
        if(!empty($FieldConfig['blank'])) {
            array_unshift($FieldConfig['options'], '_blank');
        }
        parent::__construct($fieldId, $FieldConfig, $Form);
    }
}
