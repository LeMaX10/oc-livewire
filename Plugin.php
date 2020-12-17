<?php declare(strict_types=1);

namespace LeMaX10\LiveWire;

use Cms\Classes\Controller;
use LeMaX10\LiveWire\Classes\LivewireServiceProvider;
use LeMaX10\LiveWire\Classes\Support\LiveWireTwigDirectives;
use LeMaX10\LiveWire\Components\Form;
use LeMaX10\LiveWire\Components\Test;
use Livewire\Livewire;
use System\Classes\PluginBase;

/**
 * Class Plugin
 * @package LeMaX10\LiveWire
 */
class Plugin extends PluginBase
{

    /**
     * @return array|string[]
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'LiveWire Integration',
            'description' => 'Simple integration LiweWire in OctoberCMS',
            'author'      => 'Vladimir Pyankov',
            'icon'        => 'icon-leaf'
        ];
    }

    public function register(): void
    {
        parent::register();

        $this->app->register(LivewireServiceProvider::class);
    }

    public function boot(): void
    {
        parent::boot();
        $this->mergeConfigFrom(__DIR__ .'/config/livewire.php', 'livewire');

        Livewire::component('test', Test::class);
        Livewire::component('form', Form::class);
//
//        \Event::listen('cms.page.beforeDisplay',
//            static function (\Cms\Classes\Controller $controller, string $url, $page) {
//                dd($controller, $url, $page);
//        });
    }

    /**
     * @return array
     */
    public function registerComponents(): array
    {
        return [

        ];
    }

    public function registerMarkupTags(): array
    {
        return [
            'filters' => [
                'lw' =>  [LiveWireTwigDirectives::class, 'this']
            ],
            'functions' => [
                'livewire'        => [LiveWireTwigDirectives::class, 'livewire'],
                'livewireStyles'  => [LiveWireTwigDirectives::class, 'livewireStyles'],
                'livewireScripts' => [LiveWireTwigDirectives::class, 'livewireScripts']
            ]
        ];
    }
}
