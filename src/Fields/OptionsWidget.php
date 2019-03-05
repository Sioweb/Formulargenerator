<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Core\Form;

class OptionsWidget extends Field
{
    public function __construct($fieldId, array $FieldConfig, Form $form)
    {
        // die('<pre>' . print_r($FieldConfig, true));
        parent::__construct($fieldId, $FieldConfig, $form);
    }
}
