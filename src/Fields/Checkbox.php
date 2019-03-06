<?php

declare (strict_types = 1);

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Attributes\Options;
use Sioweb\Lib\Formgenerator\Core\Form;

class Checkbox extends Field
{
    public function __construct($fieldId, array $FieldConfig, Form $Form)
    {
        parent::__construct($fieldId, $FieldConfig, $Form);
        $Options = new Options($this);
        $FieldOptions = $Options->getValue();
        if(!empty($FieldOptions)) {
            $this->value = $FieldOptions;
            $this->name .= '[]';
        }
    }

    public function getValue()
    {
        if (is_array($this->value)) {
            $Values = [];
            foreach ($this->value as $key => $option) {
                if (!empty($option['active'])) {
                    $Values[] = $option['key'];
                }
            }
            return $Values;
        } else {
            return $this->value;
        }

        return false;
    }
}
