<?php

namespace Sioweb\Lib\Formgenerator\Core;

use Sioweb\Lib\Formgenerator\Fields\Subpalette AS FieldSubpalette;

class Subpalette
{
    private $subpalette = [];

    public function __construct($Name, Array $Settings)
    {
        $this->subpalette = ['subpalette' => new FieldSubpalette($Name, ['fields' => $Settings])];
    }

    public function getFieldsets()
    {
        return $this->subpalette;
    }
}