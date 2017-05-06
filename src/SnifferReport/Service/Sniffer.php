<?php

namespace SnifferReport\Service;

abstract class Sniffer
{
    /**
     * Run PHPCS on given files.
     *
     * @param array $files
     * @param array $options
     *
     * @return array
     */
    public static function sniffFiles(array $files, $standards, array $options)
    {
        $results = [];
        foreach ($files as $file) {
            // @fixme: check if file fits include/exclude criteria before sniffing.
            $sniff_result = self::sniffFile($file, $standards);
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
     * @param string $file
     * @param string $standards
     *
     * @return string|null
     */
    private static function sniffFile($file, $standards)
    {
        $command = COMPOSER_VENDOR_DIR . '/bin/phpcs';

        $command .= " --standard={$standards}";

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
