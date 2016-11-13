<?php

namespace SnifferReport\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use \ZipArchive;


abstract class FilesHandler {

  /**
   * Handles the given file
   *
   * @param $file_name
   * @param $mime_type
   *
   * @return array
   */
  public static function handle($file_name, $mime_type) {
    // @todo: add support for other types of compressed files.
    if ($mime_type == 'application/zip') {
      return self::extractZipFile($file_name);
    }

    return [FILES_DIRECTORY_ROOT . '/' . $file_name];
  }

  /**
   * Extracts the given zip file.
   *
   * @param $file_name
   *
   * @return array
   */
  private static function extractZipFile($file_name) {
    $zip_file_uri = FILES_DIRECTORY_ROOT . '/' . $file_name;

    $zip = new ZipArchive();
    $zip->open($zip_file_uri);
    $zip->extractTo(FILES_DIRECTORY_ROOT);
    $zip->close();

    // Remove zip file from the system.
    $fs = new Filesystem();
    $fs->remove($zip_file_uri);

    return self::scanFolder(FILES_DIRECTORY_ROOT);
  }

  /**
   * Scans folder to get all files inside.
   *
   * @param $dir
   *
   * @return array
   */
  public static function scanFolder($dir) {
    $files = [];
    $finder = new Finder();
    $finder->files()->in($dir);
    foreach ($finder as $file) {
      $files[] = $file->getRealPath();
    }
    return $files;
  }
}
