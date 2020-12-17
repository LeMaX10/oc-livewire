<?php declare(strict_types=1);

namespace LeMaX10\LiveWire\Classes\Support;

use Cms\Classes\CmsException;
use Cms\Classes\Controller;
use Cms\Classes\Theme;
use Livewire\Component;
use Livewire\Livewire;
use October\Rain\Support\Str;
use Twig\Template;

/**
 * Class LiveWireComponent
 * @package LeMaX10\LiveWire\Classes\Support
 */
abstract class LiveWireComponent extends Component
{
    public $alias;

    /**
     * @var string Specifies the component directory name.
     */
    protected $dirName;

    /**
     * @var \Cms\Classes\Controller Controller object.
     */
    protected $controller;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * LiveWireComponent constructor.
     * @param $id
     */
    public function __construct($id)
    {
        parent::__construct($id);
        $this->controller = app(Controller::class);
        $this->dirName = strtolower(str_replace('\\', '/', Str::normalizeClassName(get_called_class())));

        if (empty($this->alias)) {
            $componentName = last(explode('\\', Str::normalizeClassName(get_called_class())));
            $this->alias = strtolower($componentName);
        }
    }

    /**
     * @return Template
     */
    public function render(): Template
    {
        return $this->renderPartial($this->alias .'::default');
    }

    /**
     * Returns the absolute component path.
     * @return string
     */
    public function getPath(): string
    {
        return plugins_path() . $this->dirName;
    }

    /**
     * Looks up the URL for a supplied page and returns it relative to the website root.
     * @param mixed $name Specifies the Cms Page file name.
     * @param array $parameters Route parameters to consider in the URL.
     * @param bool $routePersistence By default the existing routing parameters will be included
     * when creating the URL, set to false to disable this feature.
     * @return void
     */
    public function redirectPage($name, $parameters = [], $routePersistence = true): void
    {
        $this->redirectTo = $this->controller->pageUrl($name, $parameters, $routePersistence);
    }

    /**
     * @param $name
     * @return Template
     */
    protected function renderPartial($name, array $params = []): Template
    {
        $activeTheme = $this->controller->getTheme();
        [$componentAlias, $partialName] = explode('::', $name);
        $this->params = $params;

        /*
         * Check if the theme has an override
         */
        $partial = LiveWireComponentPartial::loadOverrideCached($activeTheme, $this, $partialName);

        /*
         * Check the component partial
         */
        if ($partial === null) {
            $partial = LiveWireComponentPartial::loadCached($this, $partialName);
        }

        CmsException::mask($partial, 400);
        $this->controller->getLoader()->setObject($partial);
        $template = $this->controller->getTwig()->loadTemplate($partial->getFilePath());
        CmsException::unmask();

        return $template;
    }

    /**
     * @inheritDoc
     */
    public function output($errors = null): string
    {
        $engine = app('view.engine.resolver')->resolve('twig');
        $engine->startLivewireRendering($this);
        $view = $this->render();
        $this->normalizePublicPropertiesForJavaScript();

        throw_unless($view instanceof Template,
            new \Exception('"render" method on ['.get_class($this).'] must return instance of ['.Template::class.']'));

        $this->setErrorBag(
            $errorBag = $errors ?: ($this->controller->getTwig()->getGlobals()['errors'] ?? $this->getErrorBag())
        );

        $this->params['errors'] = $errorBag;
        $this->params['_instance'] = $this;

        Livewire::dispatch('view:render', $view);

        $engine->endLivewireRendering();

        return $view->render($this->params + $this->getPublicPropertiesDefinedBySubClass());
    }
}
