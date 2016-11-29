<?php

namespace SnifferReport\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SnifferReport\Service\Validator;
use SnifferReport\Service\FilesHandler;
use SnifferReport\Service\GitHandler;
use SnifferReport\Service\Sniffer;
use SnifferReport\Service\SniffParser;
use Symfony\Component\Filesystem\Filesystem;
use \Exception;

class MainController {

  /**
   * Processes the POSTed data.
   *
   * @param Application $app
   * @param Request $request
   *
   * @return JsonResponse
   */
  public static function processSniff(Application $app, Request $request) {
    $file = $request->files->get('file');
    $git_url = $request->get('git_url');
    $options = $request->get('options');

    $valid_git_url = (is_null($git_url) || !Validator::isGitUrl($git_url));
    if ($valid_git_url && is_null($file)) {
      // @todo: make class to handle errors.
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

    if (!Validator::areStandardsValid($options->standards)) {
      return $app->json(json_decode('{"status":"error","message":"One or more standards are not valid. Please try again with different values."}'), 400);
    }

    if (!Validator::areExtensionsValid($options->extensions)) {
      return $app->json(json_decode('{"status":"error","message":"One or more extensions are not valid. Please try again with different values."}'), 400);
    }

    $standards = implode(',', $options->standards);
    $extensions = implode(',', $options->extensions);

    $sniff_result = Sniffer::sniffFiles($files, $standards, $extensions);

    // Delete all files that were generated in this request.
    $fs = new Filesystem();
    $fs->remove(FILES_DIRECTORY_ROOT);

    try {
      $sniffParser = new SniffParser($app['pdo']);
      $response = $sniffParser->parseSniff($sniff_result);
    }
    catch (\PDOException $e) {
      return $app->json(json_decode('{"status":"error","message":"Error when trying to save sniff: ' . $e->getMessage() . '"}'), 500);
    }

    return $app->json($response, 200);
  }
}
