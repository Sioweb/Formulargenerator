<?php

namespace Sioweb\Lib\Formgenerator\Attributes;

use Sioweb\Lib\Formgenerator\Fields\Field;

class Attributes
{
    /**
     * @param private $strAttr
     * Enthält Standard-Attribute damit keine Fehler ausgeworfen werden.
     * Jedes Formular-Element / Template kann eigene Standard-Attribute besitzen.
     */
    public static function getAttributes()
    {
        return [
            'std' => ['label', 'value', 'id', 'name', 'placeholder', 'attribute', 'required', 'maxlength', 'size', 'class', 'title', 'id', 'readonly', 'disabled', 'autocomplete', 'autofocus', 'form', 'width', 'height', 'list', 'pattern', 'step'],
            'text' => ['type', 'min', 'max'],
            'hidden' => ['type'],
            'button' => ['type'],
            'select' => ['size' => 1, 'active', 'multiple'],
            'radio' => ['type', 'active', 'multiple'],
            'checkbox' => ['type', 'active', 'multiple' => 1],
            'textarea' => ['cols' => 50, 'rows' => 10],
            'submit' => ['type', 'formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget'],
        ];
    }

    public function fetchField(Field $Field)
    {
        foreach (self::getAttributes() as $type => $attrs) {
            if ($type == 'std' || $type == $Field->template) {
                foreach ($attrs as $key => $attr) {
                    if (!is_numeric($key) && empty($Field->$key)) {
                        $Field->$key = $attr;
                    } elseif (is_numeric($key) && empty($Field->$attr)) {
                        $Field->$attr = "";
                    }
                }
            }
        }

        $this->format($Field);
    }

    /**
     * @brief Formatiert Attribute damit diese auch ohne Angaben valide ausgegeben werden können.
     */
    public function format(Field $Field)
    {
        if (empty($Field->id)) {
            $Field->id = $Field->type . '_' . $Field->fieldId;
            $arrID = explode('_', $Field->id);
            $id = end($arrID);
            /* checkbox_checkbox / checkbox_checkbox_1 / checkbox_checkbox_2 / ... */
            $Field->id = (is_numeric($id) ? str_replace('_' . $id, '_' . ($id + 1), $Field->id) : $Field->id . '_1');
        }
    }

    public function render($Attribute, $Value)
    {
        if (!in_array($Attribute, ['require', 'selected', 'checked', 'readonly', 'disable'])) {
            return $Attribute . '="' . $Value . '"';
        }

        if (!empty($Value)) {
            return $Attribute;
        }

        return false;
    }
}
