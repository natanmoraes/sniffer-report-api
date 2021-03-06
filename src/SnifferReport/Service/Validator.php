<?php

namespace SnifferReport\Service;

use \PHP_CodeSniffer;

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
     * @param array $standards
     *   The standards to be validated
     *
     * @return bool
     *   Whether the given standards are valid or not.
     */
    public static function areStandardsValid(array $standards)
    {
        foreach ($standards as $standard) {
            if (!in_array($standard, PHP_CodeSniffer::getInstalledStandards())) {
                return false;
            }
        }

        return true;
    }
}
