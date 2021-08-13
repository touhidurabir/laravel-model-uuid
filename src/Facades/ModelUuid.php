<?php

namespace Touhidurabir\ModelUuid\Facades;

use Illuminate\Support\Facades\Facade;

class ModelUuid extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {

        return 'model-uuid';
    }
}