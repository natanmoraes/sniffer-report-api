<?php

namespace SnifferReport\Service;


abstract class Sniffer {
  public static function sniffFiles(array $files, $standards = '', $extensions = '') {
    $results = [];
    foreach ($files as $file) {
      $sniff_result = self::sniffFile($file, $standards, $extensions);
      if (is_null($sniff_result)) {
        continue;
      }
      $results[$file] = $sniff_result->$file;
    }

    return $results;
  }

  private static function sniffFile($file, $standards = '', $extensions = '') {
    // Fire 'phpcs' command and grab the output.
    $command = COMPOSER_VENDOR_DIR . '/bin/phpcs';

    if (!empty($standards)) {
      $command .= " --standard=$standards";
    }

    // @fixme: handle extensions properly (ex: the code is trying to sniff a .json file)
    if (!empty($extensions)) {
      $command .= " --extensions=$extensions";
    }

    $command .= ' --report=json';

    $command .= " $file";

    $command = escapeshellcmd($command);

    exec($command, $output);

    if (is_null($output)) {
      return NULL;
    }

    $result = json_decode(reset($output));

    return $result->files;
  }
}
