<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Core\Form;

class OptionsWidget extends Field
{
    public function __construct($fieldId, array $FieldConfig, Form $form)
    {
        parent::__construct($fieldId, $FieldConfig, $form);
        
        if(empty($this->value)) {
            $this->value = [
                ['key'=>'', 'value'=>'']
            ];
        }
    }
}
