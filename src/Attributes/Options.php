<?php

declare (strict_types = 1);

namespace Sioweb\Lib\Formgenerator\Attributes;

class Options
{
    private $field = null;

    public function __construct(\Sioweb\Lib\Formgenerator\Fields\FieldInterface $Field)
    {
        $this->field = $Field;
    }

    public function getValue()
    {
        if (empty($this->field->options)) {
            return [];
        }

        $Column = 'value';
        if (!empty($this->field->valueColumn) && empty($this->field->value)) {
            $Column = $this->field->valueColumn;
        }
        $FirstOption = current($this->field->options);


        if (!is_array($FirstOption)) {
            foreach ($this->field->options as $key => $option) {
                $this->field->options[$key] = [
                    'key' => $option,
                    'value' => $option,
                ];

                if (!empty($this->field->{$Column}) && $this->field->{$Column} === $option) {
                    $this->field->options[$key]['active'] = 1;
                }
            }
        } elseif(is_array($this->field->{$Column})) {
            foreach ($this->field->options as $key => $option) {
                if (!empty($this->field->{$Column}) && in_array($option['key'], $this->field->{$Column})) {
                    $this->field->options[$key]['active'] = 1;
                }
            }
        } elseif (is_array($FirstOption)) {
            foreach ($this->field->options as $key => $option) {
                if ($this->field->{$Column} == $option['key']) {
                    $this->field->options[$key]['active'] = 1;
                }
            }
        }

        if (!empty($this->field->blank)) {
            array_unshift($this->field->options, [
                'key' => '_blank',
                'value' => '_blank',
            ]);
        }

        return $this->field->options;
    }
}
