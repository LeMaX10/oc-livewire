<?php declare(strict_types=1);

namespace LeMaX10\LiveWire\Classes;

use Cms\Classes\Controller;
use Cms\Classes\Theme;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use LeMaX10\LiveWire\Classes\Support\LivewireComponentsFinder;
use LeMaX10\LiveWire\Classes\Twig\TwigCompiler;
use Livewire\LivewireComponentsFinder as LivewireComponentsFinderBase;
use Livewire\LivewireServiceProvider as LivewireServiceProviderBase;
use LeMaX10\LiveWire\Classes\Support\LivewireViewCompilerEngine;


/**
 * Class LivewireServiceProvider
 * @package LeMaX10\LiveWire\Classes
 */
class LivewireServiceProvider extends LivewireServiceProviderBase
{
    /**
     *
     */
    public function register(): void
    {
        $this->registerTestMacros();
        $this->registerRouteMacros();
        $this->registerLivewireSingleton();
        $this->registerComponentAutoDiscovery();
    }

    /**
     *
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerCommands();
        $this->registerViewCompilerEngine();
        $this->registerHydrationMiddleware();

        $this->bypassTheseMiddlewaresDuringLivewireRequests([
            TrimStrings::class,
            ConvertEmptyStringsToNull::class
        ]);
    }

    protected function registerComponentAutoDiscovery()
    {
        // We will generate a manifest file so we don't have to do the lookup every time.
        $defaultManifestPath = storage_path('/framework/livewire-components.php');

        $this->app->singleton(LivewireComponentsFinderBase::class, static function () use ($defaultManifestPath) {
            return new LivewireComponentsFinder(
                app('files'),
                config('livewire.manifest_path') ?: $defaultManifestPath,
                Theme::getActiveTheme()->getPath() .'/livewire'
            );
        });
    }


    protected function registerViewCompilerEngine()
    {
        // This is a custom view engine that gets used when rendering
        // Livewire views. Things like letting certain exceptions bubble
        // to the handler, and registering custom directives like: "@this".
        $this->app->make('view.engine.resolver')->register('twig', function () {
            return new LivewireViewCompilerEngine(new TwigCompiler(app(Controller::class)->getTwig()));
        });
    }
}
