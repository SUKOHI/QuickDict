<?php namespace Sukohi\QuickDict\Facades;

use Illuminate\Support\Facades\Facade;

class QuickDict extends Facade {

    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'quick-dict'; }

}