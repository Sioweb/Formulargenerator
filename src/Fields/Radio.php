<?php

declare (strict_types = 1);

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Attributes\Options;
use Sioweb\Lib\Formgenerator\Core\Form;

class Radio extends Field
{
    public function __construct($fieldId, array $FieldConfig, Form $Form)
    {
        parent::__construct($fieldId, $FieldConfig, $Form);
        $Options = new Options($this);
        $this->value = $Options->getValue();
    }

    public function setValue($value)
    {
        if (is_array($this->value)) {
            foreach ($this->value as &$option) {
                if ($option['key'] == $value) {
                    $option['active'] = 1;
                }
            }
        } else {
            $this->value = $value;
        }
    }

    public function getValue()
    {
        if (is_array($this->value)) {
            foreach ($this->value as $key => $option) {
                if (!empty($option['active'])) {
                    return $option['key'];
                }
            }
        } else {
            return $this->value;
        }

        return false;
    }
}
