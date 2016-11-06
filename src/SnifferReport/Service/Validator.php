<?php

namespace SnifferReport\Service;

abstract class Validator {

  /**
   * Checks if a given url is a valid Git url.
   *
   * @param $url
   *   The URL to be checked.
   *
   * @return bool
   *   Whether the given URL is valid or not.
   */
  public static function isGitUrl($url) {
    if (!$url) {
      return FALSE;
    }

    $patterns = array(
      '#git.drupal.org/project#',
      '#git.drupal.org/sandbox#',
      '#github.com#',
    );

    $valid = FALSE;
    foreach ($patterns as $pattern) {
      if (preg_match($pattern, $url)) {
        $valid = TRUE;
        break;
      }
    }

    return $valid;
  }

  /**
   * Checks if given standards are valid.
   *
   * @param array $standards
   *   The standards to be validated
   *
   * @return bool
   *   Whether the given standards are valid or not.
   */
  public static function areStandardsValid(array $standards) {
    foreach ($standards as $standard) {
      if (!in_array($standard, self::getValidStandards())) {
        return FALSE;
      }
    }

    return TRUE;
  }

  public static function areExtensionsValid(array $extensions) {
    foreach ($extensions as $extension) {
      if (!in_array($extension, self::getValidFileExtensions())) {
        return FALSE;
      }
    }

    return TRUE;
  }

  private static function getValidStandards() {
    return [
      'Drupal',
      'DrupalSecure',
      'DrupalPractice',
    ];
  }

  private static function getValidFileExtensions() {
    return [
      'php',
      'module',
      'inc',
      'install',
      'test',
      'profile',
      'theme',
      'js',
      'css',
      'info',
      'txt',
    ];
  }
}
