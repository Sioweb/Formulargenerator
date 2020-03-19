<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Attributes\Attributes;
use Sioweb\Lib\Formgenerator\Core\Form;

class Field implements FieldInterface
{

    public $form = null;

    public $field = null;

    public $attributes = [];

    public function __construct($fieldId, array $FieldConfig, Form $form)
    {
        $this->form = $form;

        $this->fieldId = $fieldId;
        $this->assign($FieldConfig);

        if ($this->type === 'default') {
            $this->type = 'text';
        }

        $oAttributes = new Attributes;
        $oAttributes->fetchField($this);

        $this->template = $this->type;

        $this->field = $this;

        $this->updateValue();

        if (!empty($this->submitOnChange)) {
            $this->attributes[] = 'onchange="this.form.submit();"';
        }

        $this->form = $this->form->getSettings();
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    protected function updateValue()
    {
        $settings = $this->form->getSettings();
        if (empty($_POST) && empty($settings['updateValues'])) {
            return;
        }

        $postValue = $this->form->getFieldValues();
        foreach ($this->names as $key) {
            $postValue = $postValue[$key];
        }

        $this->value = $postValue;
    }

    public function getAttributes($ignore = [])
    {
        $oAttributes = new Attributes();
        $Attributes = $this->attributes;
        foreach (Attributes::getAttributes() as $type => $attrs) {
            if ($type == 'std' || $type == $this->template) {
                foreach ($attrs as $key => $attr) {
                    if (!in_array($attr, array_merge(['id', 'label', 'form'], $ignore)) && !empty($this->{$attr})) {
                        // $Attributes[] = $attr . '="' . $this->{$attr} . '"';
                        $strAttribute = $oAttributes->render($attr, $this->{$attr});
                        if (!empty($strAttribute)) {
                            $Attributes[] = $strAttribute;
                        }
                    }
                }
            }
        }

        return implode(' ', $Attributes);
    }

    protected function assign($data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $this->replaceVariables($value);
        }
    }

    protected function replaceVariables($Data)
    {
        if (is_array($Data)) {
            foreach ($Data as $key => $value) {
                if (is_array($value)) {
                    $Data[$key] = $this->replaceVariables($value);
                } else {
                    $Data[$key] = $this->form->replaceVariables($value);
                }
            }
        } else {
            $Data = $this->form->replaceVariables($Data);
        }

        return $Data;
    }

    public function hasSubpalettes($FormSubpalettes) {
        $FieldNames = [];

        switch($this->type) {
            case 'select':
                foreach($this->value as $option) {
                    if(!empty($FormSubpalettes[$this->fieldId . '_' . $option['key']])) {
                        $FieldNames[] = $this->fieldId . '_' . $option['key'];
                    }
                }
            default:
                if(!empty($FormSubpalettes[$this->fieldId])) {
                    $FieldNames[] = $this->fieldId;
                }
            break;
        }

        return $FieldNames;
    }
}
