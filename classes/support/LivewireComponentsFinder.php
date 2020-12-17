<?php declare(strict_types=1);

namespace LeMaX10\LiveWire\Classes\Support;

use Illuminate\Filesystem\Filesystem;
use Livewire\LivewireComponentsFinder as LivewireComponentsFinderBase;

class LivewireComponentsFinder extends LivewireComponentsFinderBase
{
    public function __construct(Filesystem $files, $manifestPath, $path)
    {
        parent::__construct($files, $manifestPath, $path);
    }
}
