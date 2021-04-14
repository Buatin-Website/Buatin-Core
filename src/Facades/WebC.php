<?php

namespace Buatin\WebC\Facades;

use Illuminate\Support\Facades\Facade;

class WebC extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'webc';
    }
}
