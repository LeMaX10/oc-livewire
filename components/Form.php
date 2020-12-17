<?php namespace LeMaX10\LiveWire\Components;

use LeMaX10\LiveWire\Classes\Support\LiveWireComponent;

class Form extends LiveWireComponent
{
    public $alias = 'form';

    public function render()
    {
        return $this->renderPartial('form::default');
    }
}
