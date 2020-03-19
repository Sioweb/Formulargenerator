<?php

namespace Sioweb\Lib\Formgenerator\Core;

interface TemplateInterface
{
    public function render($FieldData, \Sioweb\Lib\Formgenerator\Core\Form $FormObj = null);
}