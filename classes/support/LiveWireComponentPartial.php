<?php


namespace LeMaX10\LiveWire\Classes\Support;


use Cms\Classes\ComponentBase;
use Cms\Classes\ComponentPartial;
use Cms\Classes\Partial;

class LiveWireComponentPartial extends ComponentPartial
{
    /**
     * @var LiveWireComponent A reference to the LiveWire component containing the object.
     */
    protected $component;

    /**
     * Creates an instance of the object and associates it with a CMS component.
     * @param LiveWireComponent $component Specifies the component the object belongs to.
     */
    public function __construct(LiveWireComponent $component)
    {
        $this->component = $component;
        $this->extendableConstruct();
    }

    public static function loadOverrideCached($theme, $component, $fileName)
    {
        $partial = Partial::loadCached($theme, strtolower($component->alias) . '/' . $fileName);

        if ($partial === null) {
            $partial = Partial::loadCached($theme, $component->alias . '/' . $fileName);
        }

        return $partial;
    }

}
