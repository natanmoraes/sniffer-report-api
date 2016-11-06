<?php

require_once '../vendor/autoload.php';

// Generate an unique directory name to store files in.
srand(time());
define('FILES_DIRECTORY_ROOT', '/tmp/' . mt_rand());
define('COMPOSER_VENDOR_DIR', '../vendor');

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use SnifferReport\Service\Validator;
use SnifferReport\Service\Sniffer;
use SnifferReport\Service\FilesHandler;
use SnifferReport\Service\GitHandler;
use SnifferReport\Service\SniffParser;
use Symfony\Component\Filesystem\Filesystem;

$app = new Application();
$app['debug'] = TRUE;

$app->get('/', function() {
  return "home";
});

// Receive files to be sniffed.
// @todo: Make different urls for git url and files
$app->post('/sniff', function(Application $app, Request $request) {
  $file = $request->files->get('file');
  $git_url = $request->get('git_url');
  $options = $request->get('options');

  $valid_git_url = (is_null($git_url) || !Validator::isGitUrl($git_url));

  if ($valid_git_url && is_null($file)) {
    return $app->json(json_decode('{"status":"error","message":"File or Git URL required"}'), 400);
  }

  if (!is_null($file)) {
    $file_name = $file->getClientOriginalName();
    $file->move(FILES_DIRECTORY_ROOT, $file_name);
    $files = FilesHandler::handle($file_name, $file->getClientMimeType());
  }
  else {
    try {
      $files = GitHandler::handle($git_url);
    }
    catch (Exception $e) {
      return $app->json(json_decode('{"status":"error","message":"Error when trying to clone git repository: ' . $e->getMessage() . '"}'), 500);
    }
  }

  $options = json_decode($options);
  $standards = implode(',', $options->standards);
  $extensions = implode(',', $options->extensions);
  $sniff_result = Sniffer::sniffFiles($files, $standards, $extensions);

  // Delete all files that were generated in this request to save server space.
  $fs = new Filesystem();
  $fs->remove(FILES_DIRECTORY_ROOT);

  $response = SniffParser::parseSniff($sniff_result);
  return $app->json($response, 200);
});

// @todo: View the complete result of a sniff.
//$app->get('/sniffs/{sid}', function($sid) {});

// @todo: View all files sniffed (no messages).
//$app->get('/sniffs/{sid}/files', function($sid) {});

// @todo: View all messages from a single sniffed file
//$app->get('/sniffs/{sid}/files/{fid}', function($sid, $fid) {});

// @todo: View a single message in a sniffed file
//$app->get('/sniffs/{sid}/files/{fid}/messages/{mid}', function($sid, $fid, $mid) {});

$app->run();
