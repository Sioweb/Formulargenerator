<?php

include '../classes/Form.php';

/* As JSON */

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

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Formgenerator</title>
  <style>fieldset + fieldset {margin-top: 20px;}fieldset > div + div { margin-top: 10px;}label {width: 100px;display: inline-block;vertical-align: top;} [type=text],textarea,select {width: 400px;}</style>
</head>
<body>
  <form action="index.php" method="post">
    <?php 
      $Form = new Form(dirname(__FILE__).'/../templates','eins',true,$FormData);
      $Form->generate(true);
    ?>
  </form>
</body>
</html>