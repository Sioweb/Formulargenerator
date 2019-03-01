<?php

namespace Sioweb\Lib\Formgenerator\Core;

class Palette
{
    private $palette = [];

    public function __construct(Array $Settings)
    {
        foreach($Settings as $fieldsetId => $fieldset) {
            $Settings[$fieldsetId] = new Fieldset($fieldsetId, $fieldset);
        }
        $this->palette = $Settings;
    }

    public function getFieldsets()
    {
        return $this->palette;
    }
}