<?php

require_once '../vendor/autoload.php';

// Generate an unique directory name to store files in.
srand(time());
define('FILES_DIRECTORY_ROOT', '/tmp/' . mt_rand());
define('COMPOSER_VENDOR_DIR', '../vendor');

use Silex\Application;
use SnifferReport\Exception\SnifferReportException;
use SnifferReport\Response\SnifferErrorResponse;
use SnifferReport\Response\SnifferSuccessResponse;

$app = new Application();
$app['debug'] = true;

// Receive files to be sniffed.
// @todo: Make different urls for git url and files
$app->post('/sniff', 'SnifferReport\\Controller\\MainController::processSniff');

// Default success response handler
$app->view(function ($response) use ($app) {
    return new SnifferSuccessResponse($response);
});

// Default error handler for expected problems.
$app->error(function (SnifferReportException $e) use ($app) {
    return new SnifferErrorResponse($e->getMessage(), $e->getCode());
});

// Generic error handler when something unexpected happens.
$app->error(function (\Exception $e) use ($app) {
    // @fixme: Add a log system with clear messages for debugging.
    return new SnifferErrorResponse('An unexpected error occurred. Please contact the support.');
});

$app->run();
