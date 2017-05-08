<?php

namespace SnifferReport\Service;

use \PHP_CodeSniffer;
use SnifferReport\Exception\UnableToParseExclusionListException;

abstract class Validator
{
    /**
     * Checks if a given url is a valid Git url.
     *
     * @param $url
     *   The URL to be checked.
     *
     * @return bool
     *   Whether the given URL is valid or not.
     */
    public static function isGitUrl($url)
    {
        if (!$url) {
            return false;
        }

        // @todo: change validation to accept any git url.
        $patterns = array(
            '#git.drupal.org/project#',
            '#git.drupal.org/sandbox#',
            '#github.com#',
        );

        $valid = false;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                $valid = true;
                break;
            }
        }

        return $valid;
    }

    /**
     * Checks if given standards are supported by the application.
     *
     * @param string $standards
     *   The standards to be validated
     *
     * @return bool
     *   Whether the given standards are valid or not.
     */
    public static function areAllStandardsValid($standards)
    {
        $standards = explode(',', $standards);
        $installed_standards = PHP_CodeSniffer::getInstalledStandards();
        foreach ($standards as $standard) {
            if (!in_array($standard, $installed_standards)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validates if given file should be sniffed based on sent options.
     *
     * @param string $file
     * @param string $exclusion_list
     *
     * @return bool
     *
     * @throws UnableToParseExclusionListException
     */
    public static function fileShouldBeSniffed($file, $exclusion_list)
    {
        $file_list = json_decode($exclusion_list);

        if (!empty($exclusion_list) && empty($file_list)) {
            throw new UnableToParseExclusionListException();
        }

        foreach ($file_list as $item) {
            if (strpos($item, $file)) {
                return false;
            }
        }

        return true;
    }
}
