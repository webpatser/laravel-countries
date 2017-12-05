<?php

namespace Webpatser\Countries;

use RuntimeException;

class MigrationFailedException extends RuntimeException
{
    /**
     * @var string
     */
    protected $message = "Migration failed.";
}