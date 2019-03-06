<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Core\Form;

class Select extends Field
{
    public function __construct($fieldId, Array $FieldConfig, Form $Form)
    {
        
        parent::__construct($fieldId, $FieldConfig, $Form);
        $Column = 'value';
        if(!empty($this->valueColumn) && empty($this->value)) {
            $Column = $this->valueColumn;
        }
        $FirstOption = current($this->options);
        
        if(!is_array($FirstOption)) {
            foreach($this->options as $key => $option) {
                $this->options[$key] = [
                    'key' => $option,
                    'value' => $option
                ];

                if(!empty($this->{$Column}) && $this->{$Column} === $option) {
                    $this->options[$key]['active'] = 1;
                }
            }
        }
        if(!empty($this->blank)) {
            array_unshift($this->options, [
                'key' => '_blank',
                'value' => '_blank'
            ]);
        }

        $this->value = $this->options;
    }

    public function getValue()
    {
        // $Column = 'value';
        // if(!empty($this->valueColumn)) {
        //     $Column = $this->valueColumn;
        // }
        // die('<pre>' . print_r($this->value, true));
        if(is_array($this->value)) {
            foreach($this->value as $key => $option) {
                if(!empty($option['active'])) {
                    return $option['key'];
                }
            }
        } else {
            return $this->value;
        }

        if(!empty($this->blank)) {
            return '_blank';
        }

        return false;
    }
}
