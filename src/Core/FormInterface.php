<?php

namespace Sioweb\Lib\Formgenerator\Core;

interface FormInterface
{
    public function loadData();
    public function loadPalettes();
    public function loadFieldConfig();
}