<?php namespace LeMaX10\LiveWire\Classes\Support;

use Livewire\LivewireViewCompilerEngine as LivewireViewCompilerEngineBase;
use Livewire\ObjectPrybar;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LivewireViewCompilerEngine extends LivewireViewCompilerEngineBase
{
    protected function addLivewireDirectivesToCompiler()
    {
        $this->exposedCompiler = new ObjectPrybar($this->compiler);
        $this->tmpCustomDirectives = [];
        $this->exposedCompiler->setProperty('customDirectives', $this->tmpCustomDirectives);
    }
}
