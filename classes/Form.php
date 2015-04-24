<?php

interface iForm {
  public function fieldset($strFieldset,$strLegend = '');
  public function path($strPath);
  public function field($strType,$arrAttr = []);
  public function save();
}

class Form implements iForm {

  private $path = '';
  private $fields = null;
  private $field = null;
  private $ext = 'php';
  private $legend = [];
  private $fieldset = 'std';

  private $sql;

  private $postvalue = true;

  private $protectedValues = ['select','hidden'];
  private $output = [];

  /**
   * @param private $strAttr
   * Enthält Standard-Attribute damit keine Fehler ausgeworfen werden.
   * Jedes Formular-Element / Template kann eigene Standard-Attribute besitzen.
   */
  private $stdAttr = [
    'std'=>['label','value','id','name','placeholder','attribute'],
    'select'=>['size'=>1,'active','multiple'],
    'radio'=>['active','multiple'],
    'checkbox'=>['active','multiple'=>1],
    'textarea'=>['cols'=>50,'rows'=>10],
  ];
  

  public function __construct($path = 'templates',$fieldset = 'std',$post = true,array $formdata = array()) {
    $this->path = $path;
    $this->fieldset = $fieldset;
    $this->postvalue = $post;

    $this->setup($this->fields);
    $this->setup($this->fields->$fieldset);

    if(!empty($formdata))
      $this->fields = (object)$formdata;
  }

  public function __call($func,$values) {
    if($func == 'generate') {
      if(empty($values[0]))
        $values[0] = false;
      return $this->generate($this->fields,$values[0]);
    }
  }

  public function __set($var, $val) {
    $var = strtolower($var);
    $this->setup($this->field);
    if(empty($this->field->$var) || $var !== 'name')
      $this->field->$var = $val;
    else trigger_error('Der Name für das Feld "'.$this->field->$var.'" wurde bereits gewählt und kann nicht geändert werden!',E_USER_NOTICE);

  }

  public function __get($var) {
    if(!empty($this->field->$var) || $this->field->$var !== false)
      return $this->field->$var;
    if(!empty($this->field))
      return $this->field;
  }

  /**
   * @brief Gruppiert Felder
   * @param string $strFieldset Name der Gruppe
   * @param string $strLegend Legende die überhalb der Gruppe ausgegeben wird. Ist keine Legende angegeben, wird die Legende auch nicht gerendert.
   */
  public function fieldset($strFieldset,$strLegend = '') {
    $this->save();
    $this->fieldset = $strFieldset;
    if(!empty($strLegend))
      $this->legend[$strFieldset] = $strLegend;
  }

  /**
   * @param string $strPath ändert den Pfad der Templates
   * @return string $Path Gibt den Pfad zu den Templates zurück, wenn der Parameter $strPath leer bleibt.
   */
  public function path($strPath = '') {
    if($strPath)
      $this->path = $strPath;
    return $this->path;
  }

  public function field($strType,$arrAttr = []) {
    $this->save();
    $this->template = $strType;
    if(!empty($arrAttr))
      foreach($arrAttr as $attr => $value)
        $this->field->$attr = $value;
  }

  /**
   * @brief Speichert die Felder ab, damit sie sich nicht gegenseitig überschreiben.
   */
  public function save() {
    if($this->field !== null) {
      $this->std()->format();
      $this->setup($this->fields->{$this->fieldset});
      $this->fields->{$this->fieldset} = (object)array_merge((array)$this->fields->{$this->fieldset},[$this->field->id=>$this->field]);
    }
    $this->field = null;
    return $this;
  }

  /**
   * @brief Generiert ein Array mit den Formulardaten und gibt diese ggf. via echo aus.
   * @param $shout Ist $shout gesetzt wird das Formular via echo ausgegeben, wobei alle Elemente mit dem Wert $shout getrennt werden. Ist $shout === true werden die Elemente einfach aneinander gereiht.
   * @return array Gibt ein Array zurück mit den generierten Elementen
   */
  protected function generate($fields,$shout = false) {
    $this->save();
    foreach($fields as $type => $fieldsets) {
      $this->fieldset = $type;
      foreach($fieldsets as $field) {
        $this->field = (object)$field;
        $this->std()->format();
        $this->output[$type][] = $this->loadTemplate($this->field->template);
      }
      if($shout && !empty($this->output[$type])) {
        $this->output[$type] = $this->loadTemplate('fieldset');
      }
    }

    $this->fields = null;

    if($shout)
      echo implode(($shout===true?'':$shout),$this->output);
    return $this->output;
  }

  private function std() {
    foreach($this->stdAttr as $type => $attrs)
      if($type=='std' || $type == $this->template)
        foreach($attrs as $key => $attr) 
          $this->stdValue($key,$attr);

    if(!empty($_POST[$this->field->name]) && $this->postvalue) {
      if(!$this->field->value && !in_array($this->field->template,$this->protectedValues))
        $this->field->value = $_POST[$this->field->name];
      elseif($this->field->value == $_POST[$this->field->name])
        $this->field->active = $this->field->value;
      elseif($this->field->template !== 'hidden' && ($this->field->multiple || $this->field->template === 'select'))
        $this->field->active = $_POST[$this->field->name];
    }
    return $this;
  }

  /**
   * @brief Setzt Standard-Werte für Standard-Attribute zur verfügung.
   */
  private function stdValue($key, $attr = "") {
    if(!is_numeric($key) && empty($this->field->$key))
      $this->field->$key = $attr;
    elseif(is_numeric($key) && empty($this->field->$attr))
      $this->field->$attr = "";
  }


  /**
   * @brief Formatiert Attribute damit diese auch ohne Angaben valide ausgegeben werden können.
   */
  private function format() {
    if(empty($this->field->name))
      $this->field->name = 'field_'.count($this->fields);
    if(empty($this->field->id)) {
      $this->field->id = $this->field->template.'_'.$this->field->name;
      while(!empty($this->fields->{$this->fieldset}->{$this->field->id})) {
        $arrID = explode('_',$this->field->id);
        $id = end($arrID);
        /* checkbox_checkbox / checkbox_checkbox_1 / checkbox_checkbox_2 / ... */
        $this->field->id = (is_numeric($id)?str_replace('_'.$id,'_'.($id+1),$this->field->id):$this->field->id.'_1');
      }
    }
    return $this;
  }

  private function loadTemplate($tpl) {
    $file = rtrim($this->path,'/').'/'.$tpl.'.'.$this->ext;
    if(is_file($file)) {
      ob_start();
      include $file;
      $Output = ob_get_contents();
      ob_end_clean();
      return $Output;
    } else trigger_error('Das Template "'.$file.'" wurde nicht gefunden!',E_USER_NOTICE);
  }

  private function setup(&$val) {
    if(empty($val) || $val === null)
      $val = new stdClass;
  }

}