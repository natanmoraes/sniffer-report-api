<?php

namespace SnifferReport\Service;

use Gitonomy\Git\Admin;


abstract class GitHandler {
  public static function handle($git_url) {
    $parts = explode(' ', $git_url);
    $url = $parts[0];
    $branch = isset($parts[1]) ? $parts[1] : '';

    return self::getFilesFromGitRepository($url, $branch);
  }

  private static function getFilesFromGitRepository($url, $branch = '') {
    $repo = self::cloneGitRepository($url, $branch);
    return FilesHandler::scanFolder($repo->getPath());
  }

  private static function cloneGitRepository($url, $branch = '') {
    // Shallow clone the repository to save storage.
    $args = array('--depth', '1');

    if (!empty($branch)) {
      // Clone from specific branch.
      $args[] = '--branch';
      $args[] = $branch;
    }

    $repository = Admin::cloneRepository(FILES_DIRECTORY_ROOT, $url, $args);

    return $repository;
  }
}
