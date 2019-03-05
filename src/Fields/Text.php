<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Core\Form;

class Text extends Field
{
    public function __construct($fieldId, array $FieldConfig, Form $form)
    {
        parent::__construct($fieldId, $FieldConfig, $form);
        if(!empty($this->validation)) {
            switch(strtolower($this->validation)) {
                case 'email':
                    $this->type = 'email';
                    break;
            }
        }
    }
}
