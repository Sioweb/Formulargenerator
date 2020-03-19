<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Core\Form;

class Password extends Text
{
    public function __construct($fieldId, array $FieldConfig, Form $form)
    {
        parent::__construct($fieldId, $FieldConfig, $form);
        $this->template = 'text';
    }
}
