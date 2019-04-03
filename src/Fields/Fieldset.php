<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Attributes\Attributes;

class Fieldset
{
    public $template = 'fieldset';
    public $label = false;

    public function __construct($Name, Array $Fieldset)
    {
        $this->id = $Name;
        $this->fieldset = $this;
        $this->assign($Fieldset);
        $this->fields = array_flip($this->fields);
    }

    protected function assign($data)
    {
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function updateField($field)
    {
        $this->fields[$field->name] = $field;
    }

    public function getFields()
    {
        return array_flip($this->fields);
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
}