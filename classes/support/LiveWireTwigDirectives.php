<?php declare(strict_types=1);

namespace LeMaX10\LiveWire\Classes\Support;

use Illuminate\Support\Str;

class LiveWireTwigDirectives
{
    public static function this($instance)
    {
        return "window.livewire.find('". $instance->id ."')";
    }

    public static function livewireStyles()
    {
        return \Livewire\Livewire::styles();
    }

    public static function livewireScripts()
    {
        return \Livewire\Livewire::scripts();
    }

    public static function livewire($expression, array $params = [])
    {
        return \Livewire\Livewire::mount($expression, $params)->dom;
    }
}
