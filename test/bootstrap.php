<?php

use SetBased\Affirm\ErrorHandler;

mb_internal_encoding('UTF-8');
error_reporting(E_ALL);
date_default_timezone_set('Europe/Amsterdam');

require __DIR__.'/../vendor/autoload.php';

// Set the error handler.
$handler = new ErrorHandler();
$handler->register(false);