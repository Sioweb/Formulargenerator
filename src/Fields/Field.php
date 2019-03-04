<?php

namespace Sioweb\Lib\Formgenerator\Fields;

use Sioweb\Lib\Formgenerator\Core\Form;

class Field
{

    private $form = null;

    public $field = null;

    /**
     * @param private $strAttr
     * Enthält Standard-Attribute damit keine Fehler ausgeworfen werden.
     * Jedes Formular-Element / Template kann eigene Standard-Attribute besitzen.
     */
    private $stdAttr = [
        'std' => ['label', 'value', 'id', 'name', 'placeholder', 'attribute', 'required', 'maxlength', 'size', 'class', 'title', 'id', 'readonly', 'disabled', 'autocomplete', 'autofocus', 'form', 'width', 'height', 'list', 'pattern', 'step'],
        'number' => ['min', 'max'],
        'select' => ['size' => 1, 'active', 'multiple'],
        'radio' => ['active', 'multiple'],
        'checkbox' => ['active', 'multiple' => 1],
        'textarea' => ['cols' => 50, 'rows' => 10],
        'submit' => ['formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget'],
    ];

    public $attributes = [];

    public function __construct($fieldId, array $FieldConfig, Form $form)
    {
        $this->form = $form;

        $this->fieldId = $fieldId;
        $this->assign($FieldConfig);
        $this->std()->format();
        if ($this->type === 'default') {
            $this->type = 'text';
        }
        $this->template = $this->type;

        $this->field = $this;

        $this->updateValue();

        if (!empty($this->submitOnChange)) {
            $this->attributes[] = 'onchange="this.form.submit();"';
        }

        $this->form = $this->form->settings;
        unset($this->stdAttr);
    }

    protected function updateValue()
    {
        if (empty($_POST)) {
            return;
        }

        $postValue = $this->form->getFieldValues();
        foreach ($this->names as $key) {
            $postValue = $postValue[$key];
        }

        $this->value = $postValue;
    }

    public function getAttributes()
    {
        return implode(' ', $this->attributes);
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

    private function std()
    {
        foreach ($this->stdAttr as $type => $attrs) {
            if ($type == 'std' || $type == $this->template) {
                foreach ($attrs as $key => $attr) {
                    $this->stdValue($key, $attr);
                }
            }
        }

        if (!empty($_POST[$this->name]) && $this->postvalue) {
            if (!$this->value && !in_array($this->template, $this->protectedValues)) {
                $this->value = $_POST[$this->name];
            } elseif ($this->value == $_POST[$this->name]) {
                $this->active = $this->value;
            } elseif ($this->template !== 'hidden' && ($this->multiple || $this->template === 'select')) {
                $this->active = $_POST[$this->name];
            }

        }
        return $this;
    }

    /**
     * @brief Setzt Standard-Werte für Standard-Attribute zur verfügung.
     */
    private function stdValue($key, $attr = "")
    {
        if (!is_numeric($key) && empty($this->$key)) {
            $this->$key = $attr;
        } elseif (is_numeric($key) && empty($this->$attr)) {
            $this->$attr = "";
        }
    }

    /**
     * @brief Formatiert Attribute damit diese auch ohne Angaben valide ausgegeben werden können.
     */
    private function format($arr = [])
    {
        if (empty($this->id)) {
            $this->id = $this->type . '_' . $this->fieldId;
            $arrID = explode('_', $this->id);
            $id = end($arrID);
            /* checkbox_checkbox / checkbox_checkbox_1 / checkbox_checkbox_2 / ... */
            $this->id = (is_numeric($id) ? str_replace('_' . $id, '_' . ($id + 1), $this->id) : $this->id . '_1');
        }
        return $this;
    }
}
