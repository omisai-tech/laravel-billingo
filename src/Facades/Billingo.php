<?php

namespace Omisai\Billingo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Omisai\Billingo\Billingo
 */
class Billingo extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Omisai\Billingo\Billingo::class;
    }
}
