<?php

namespace Sioweb\Lib\Formgenerator\Core;

use Sioweb\Lib\Formgenerator\Fields\Fieldset;

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