<?php namespace LeMaX10\LiveWire\Components;

use LeMaX10\LiveWire\Classes\Support\LiveWireComponent;

class Test extends LiveWireComponent
{
    public $name;
    public $email;

    public function updated($field)
    {
        $this->validateOnly($field, [
            'name' => 'min:6',
            'email' => 'email',
        ]);
    }

    public function saveContact()
    {
        $validatedData = $this->validate([
            'name' => 'required|min:6',
            'email' => 'required|email',
        ]);

        $this->name = request()->input('name');
        $this->email = request()->input('email');
    }
}
