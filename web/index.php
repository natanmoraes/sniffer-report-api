<?php

require_once '../config.php';
require_once '../vendor/autoload.php';

// Generate an unique directory name to store files in.
srand(time());
define('FILES_DIRECTORY_ROOT', '/tmp/' . mt_rand());
define('COMPOSER_VENDOR_DIR', '../vendor');

use Silex\Application;

$app = new Application();
$app['debug'] = TRUE;

$app['pdo'] = function() use ($config) {
  $pdo = new \PDO($config['pdo_dsn'], $config['pdo_username'], $config['pdo_password']);
  $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  return $pdo;
};

// Receive files to be sniffed.
// @todo: Make different urls for git url and files
$app->post('/sniff', 'SnifferReport\\Controller\\MainController::processSniff');

// @todo: View the complete result of a sniff.
//$app->get('/sniffs/{sid}', function($sid) {});

// @todo: View all files sniffed (no messages).
//$app->get('/sniffs/{sid}/files', function($sid) {});

// @todo: View all messages from a single sniffed file
//$app->get('/sniffs/{sid}/files/{fid}', function($sid, $fid) {});

// @todo: View a single message in a sniffed file
//$app->get('/sniffs/{sid}/files/{fid}/messages/{mid}', function($sid, $fid, $mid) {});

$app->run();
