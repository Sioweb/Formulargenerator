<?php

error_reporting(E_ALL);

// echo '<pre>'.print_r($_POST,1).'</pre>';

$FormData = [
  'first' => [
    'legend' => 'First Fieldset',
    'name' => ['template'=>'text','value'=>'Sascha Weidner','label'=>'Name','placeholder'=>'Please enter here!'],
    'lastname' => ['template'=>'text','label'=>'Lastname','placeholder'=>'Please enter here!'],
  ],
  'second' => [
    'legend' => 'Second Fieldset',
    'checkbox' => [
      ['template'=>'checkbox','value'=>1,'label'=>'Checkbox 1'],
      ['template'=>'checkbox','value'=>2,'label'=>'Checkbox 2'],
      ['template'=>'checkbox','value'=>3,'label'=>'Checkbox 3'],
    ],
  ],
  'third' => [
    'legend' => 'Third Fieldset',
    'radio' => [
      ['template'=>'radio','value'=>1,'label'=>'Radio 1'],
      ['template'=>'radio','value'=>2,'label'=>'Radio 2'],
      ['template'=>'radio','value'=>3,'label'=>'Radio 3'],
    ],
  ],
  'fourth' => [
    'legend' => 'Submit',
    'submit' => ['template'=>'submit','value'=>'Submit'],
  ],
];

echo '<pre>'.print_r($FormData,1).'</pre>';

include 'classes/Form.php';

$REX = [
  'ROOT' => dirname(__FILE__),
];

$Form = new Form($REX['ROOT'].'/templates','eins',true);
$Form->fieldset('eins','Eins');

/* Feld direkt mit Werten erzeugen */
$Form->field('text',['name'=>'name','label'=>'Name','placeholder'=>'Please enter here!']);
/*
  Feld nachträglich bearbeiten:
  * $Form->label = 'Feld 1';
  * $Form->placeholder = 'Please enter here!';
*/

/* Feld als Record anlegen */
$Form->field('text');
$Form->name = 'lastname';
$Form->label = 'Lastname';
$Form->placeholder = 'Please enter here!';


$Form->field('select');
$Form->name = 'salutation';
$Form->label = 'Anrede';
$Form->options = [
  'Frau' => 'Frau',
  'Herr' => 'Herr',
];


$Form->field('textarea');
$Form->name = 'message';
$Form->label = 'Message';


$Form->fieldset('zwei','Zwei');

$Form->field('hidden');
$Form->name = 'radio';
$Form->value = false;

$Form->field('radio');
$Form->name = 'radio';
$Form->label = 'Radio 1';
$Form->value = 1;

$Form->field('radio');
$Form->name = 'radio';
$Form->label = 'Radio 2';
$Form->value = 2;

$Form->field('radio');
$Form->name = 'radio';
$Form->label = 'Radio 3';
$Form->value = 3;

$Form->fieldset('drei','Drei');

$Form->field('hidden');
$Form->name = 'checkbox';
$Form->value = false;

$Form->field('checkbox');
$Form->name = 'checkbox';
$Form->label = 'Checkbox 1';
$Form->value = 1;

$Form->field('checkbox');
$Form->name = 'checkbox';
$Form->label = 'Checkbox 2';
$Form->value = 2;

$Form->field('checkbox');
$Form->name = 'checkbox';
$Form->label = 'Checkbox 3';
$Form->value = 3;

$Form->fieldset('vier','Vier');
$Form->field('submit',['value'=>'Submit']);

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Formgenerator</title>
  <style>fieldset + fieldset {margin-top: 20px;}fieldset > div + div { margin-top: 10px;}label {width: 100px;display: inline-block;vertical-align: top;} [type=text],textarea,select {width: 400px;}</style>
</head>
<body>
  <h2>Form 1</h2>

  <form action="index.php" method="post">
    <?php 
      $Form1 = new Form($REX['ROOT'].'/templates','eins',true,$FormData);
      $Form1->generate(true);
    ?>
  </form>
  <br><br>
  <h2>Form 2</h2>
  <form action="index.php" method="post">
    <?php $Form->generate(true);?>
  </form>
</body>
</html>