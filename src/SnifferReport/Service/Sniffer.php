<?php

namespace SnifferReport\Service;

abstract class Sniffer
{
  /**
   * Run PHPCS on given files.
   *
   * @param array $files
   * @param string $standards
   * @param string $extensions
   *
   * @return array
   */
    public static function sniffFiles(array $files, $standards = '', $extensions = '')
    {
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

  /**
   * Run PHPCS in a single file.
   *
   * @param $file
   * @param string $standards
   * @param string $extensions
   *
   * @return string|null
   */
    private static function sniffFile($file, $standards = '', $extensions = '')
    {
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

        // Run phpcs and grab the output.
        exec($command, $output);

        if (is_null($output)) {
            return null;
        }

        $result = json_decode(reset($output));

        return $result->files;
    }
}
