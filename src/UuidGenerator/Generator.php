<?php

namespace Touhidurabir\ModelUuid\UuidGenerator;

use Throwable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Generator {

    /**
     * Generate the uuid type 4
     *
     * @return string
     */
    public static function uuid4() {

        return Uuid::uuid4()->toString();
    }

    /**
     * Get the UUID
     *
     * @return string
     */
    public function generate() {

        return static::uuid4();
    }
    
}