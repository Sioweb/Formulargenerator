<?php

namespace Sioweb\Lib\Formgenerator\Core;

use stdClass;

class Form
{

    private $path = '';
    private $fields = null;
    private $field = null;
    private $ext = 'php';
    private $legend = [];
    private $fieldset = 'std';
    private $triggerPalette = null;

    private $fieldValues = [];
    private $variables = [];

    private $dataContainer = null;

    private $settings = [
        'id' => '',
        'formname' => '',
        'noFieldsets' => false,
        'updateValues' => false,
        'brackets' => ['[{', '}]'],
        'fieldname' => '%FIELDNAME%',
        'defaultPalette' => 'default',
    ];

    private $postvalue = true;

    private $protectedValues = ['select', 'hidden'];
    private $output = [];
    private $TemplateLoader = null;

    public function __construct($TemplateLoader = null, $DataContainer = null)
    {
        if (!empty($TemplateLoader)) {
            if ($TemplateLoader instanceof TemplateInterface) {
                $this->TemplateLoader = $TemplateLoader;
            } else {
                // throw execption
            }
        }
        if (!empty($DataContainer)) {
            if ($DataContainer instanceof FormInterface) {
                $this->dataContainer = $DataContainer;
            } else {
                // throw execption
            }
        }
    }

    public function setTemplatePath($path)
    {
        $this->path = $path;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getFieldValues()
    {
        if (!empty($_POST)) {
            return array_replace_recursive($_POST, $this->fieldValues);
        }

        return $this->fieldValues;
    }

    public function setFieldValues($Values)
    {
        $this->fieldValues = $Values;
    }

    public function setFormData($formdata = [])
    {
        if (empty($formdata) && !empty($this->dataContainer)) {
            $formdata = $this->dataContainer->loadData();
        }
        $this->settings = array_merge($this->settings, array_diff_key($formdata['form'], ['palettes' => '', 'fields' => '']));
        if (empty($this->settings['id'])) {
            $this->settings['id'] = md5(uniqid(microtime()));
        }
        $this->prepareFormData($formdata);
    }

    public function replaceVariables($String, $Data = [])
    {
        if (strpos($String, $this->settings['brackets'][0]) === false) {
            return $String;
        }

        if (empty($Data)) {
            $Data = $this->variables;
        }
        
        $Data = array_merge($this->settings, $Data);
        $brackets = $this->settings['brackets'];
        array_walk($brackets, function (&$item) use ($brackets) {
            $item = addcslashes($item, implode('', (array) $brackets));
        });


        foreach ($Data as $Variable => $Replacement) {
            if (preg_match('|' . $brackets[0] . '[ ]+\${1}' . strtoupper($Variable) . '[ ]+' . $brackets[1] . '|i', $String)) {
                $String = preg_replace('|' . $brackets[0] . '[ ]+\${1}' . strtoupper($Variable) . '[ ]+' . $brackets[1] . '|i', $Replacement, $String);
            }
        }

        return $String;
    }

    private function prepareFormData(&$formdata)
    {
        if (empty($formdata['form']['palettes'])) {
            // throw error palettes
            return;
        }
        if (empty($formdata['form']['fields'])) {
            // throw error fields
            return;
        }

        foreach ($formdata['form']['palettes'] as $name => &$palette) {
            $palette = new Palette($palette);
        }

        $FieldsNamespace = '\\Sioweb\\Lib\\Formgenerator\\Fields\\';
        if (!empty($this->settings['fieldnamespace'])) {
            $FieldsNamespace = $this->settings['fieldnamespace'];
        }
        foreach ($formdata['form']['fields'] as $fieldId => &$field) {
            if (is_array($field)) {

                if(empty($field['type'])) {
                    continue;
                }
                
                if (empty($field['name'])) {
                    $field['name'] = $this->replaceVariables($this->settings['fieldname'], ['fieldname' => $fieldId]);
                }

                preg_match_all('#([a-z]+)|\[([^\]]+)\]#', $field['name'], $matches);
                $field['names'] = array_merge(array_filter($matches[1]), array_filter($matches[2]));

                if ($field['type'] === 'default') {
                    $Classname = $FieldsNamespace . 'Text';
                } else {
                    $Classname = $FieldsNamespace . ucfirst($field['type']);
                }

                $field = new $Classname($fieldId, $field, $this);

                if (!empty($field->palette)) {
                    $this->triggerPalette = $field;
                }
            }
        }
        $this->palettes = $formdata['form']['palettes'];
        $this->fields = $formdata['form']['fields'];
    }

    public function __set($var, $val)
    {
        $var = strtolower($var);
        $this->setup($this->field);
        if (empty($this->field->$var) || $var !== 'name') {
            $this->field->$var = $val;
        } else {
            trigger_error('Der Name für das Feld "' . $this->field->$var . '" wurde bereits gewählt und kann nicht geändert werden!', E_USER_NOTICE);
        }
    }

    public function __get($var)
    {
        if (!empty($this->field->$var) || $this->field->$var !== false) {
            return $this->field->$var;
        }

        if (!empty($this->field)) {
            return $this->field;
        }
    }

    public function stringVariables($Variables)
    {
        $this->variables = array_replace_recursive($this->variables, $Variables);
    }

    public function field($strType, $arrAttr = [])
    {
        $this->save();
        $this->template = $strType;
        if (!empty($arrAttr)) {
            foreach ($arrAttr as $attr => $value) {
                $this->field->$attr = $value;
            }
        }

    }

    /**
     * @brief Speichert die Felder ab, damit sie sich nicht gegenseitig überschreiben.
     */
    public function save()
    {
        if ($this->field !== null) {
            $this->std()->format();
            $this->setup($this->fields->{$this->fieldset});
            $this->fields->{$this->fieldset} = (object) array_merge((array) $this->fields->{$this->fieldset}, [$this->field->id => $this->field]);
        }
        $this->field = null;
        return $this;
    }

    /**
     * @brief Generiert ein Array mit den Formulardaten und gibt diese ggf. via echo aus.
     * @param $shout Ist $shout gesetzt wird das Formular via echo ausgegeben, wobei alle Elemente mit dem Wert $shout getrennt werden. Ist $shout === true werden die Elemente einfach aneinander gereiht.
     * @return array Gibt ein Array zurück mit den generierten Elementen
     */
    public function generate($shout = false)
    {
        $Palette = $this->settings['defaultPalette'];
        if (!empty($this->triggerPalette->value)) {
            $_Palette = $this->triggerPalette->getValue();
            if($_Palette !== false && $_Palette !== '_blank') {
                $Palette = $_Palette;
            }
        }

        $Palette = $this->palettes[$Palette];
        $PaletteFieldsets = $Palette->getFieldsets();

        foreach ($PaletteFieldsets as $fieldsetId => $fieldset) {
            $Fields = $fieldset->getFields();
            if (empty($Fields)) {
                continue;
            }

            $skipFieldset = true;
            foreach ($Fields as $fieldName) {
                if (empty($this->fields[$fieldName]) || !empty($this->fields[$fieldName]->hideOnEdit)) {
                    continue;
                }
                $this->field = null;
                $this->field = $this->fields[$fieldName];
                $this->output[$fieldsetId][] = $this->field->raw = $this->loadTemplate($this->field->type, $this->field);
                $fieldset->updateField($this->field);
                $skipFieldset = false;
            }
            if (!$skipFieldset) {
                $this->output[$fieldsetId] = $this->field->raw = $this->loadTemplate('fieldset', $fieldset);
            }
        }
        $this->field = null;
        $this->fields = null;

        if ($shout) {
            echo implode(($shout === true ? '' : $shout), $this->output);
        }

        return $this->output;
    }

    private function loadTemplate($tpl, $Data = [])
    {
        if (!empty($this->TemplateLoader)) {
            $Output = $this->TemplateLoader->render($Data, $this);
            if (!empty($Output)) {
                return $Output;
            }
        }
        $file = rtrim($this->path, '/') . '/' . $tpl . '.' . $this->ext;

        if (is_file($file)) {
            ob_start();
            include $file;
            $Output = ob_get_contents();

            ob_end_clean();
            return $Output;
        } else {
            trigger_error('Das Template "' . $file . '" wurde nicht gefunden!', E_USER_NOTICE);
        }

    }

    private function setup(&$val)
    {
        if (empty($val) || $val === null) {
            $val = new stdClass;
        }

    }
}
