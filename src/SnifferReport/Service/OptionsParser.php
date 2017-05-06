<?php
namespace SnifferReport\Service;

use SnifferReport\Exception\UnableToParseOptionsException;

class OptionsParser
{
    /**
     * Parses the options sent by the user.
     *
     * @param string $options
     *
     * @return array|null $parsed_options
     *
     * @throws UnableToParseOptionsException
     */
    public static function parseOptions($options)
    {
        if (empty($options)) {
            return null;
        }

        $decoded_options = json_decode($options);
        if (empty($decoded_options)) {
            throw new UnableToParseOptionsException();
        }

        // @fixme: process $decoded_options->included_extensions
        // regex: ^.*((\.inc)|(\.php))$

        // @fixme: process $decoded_options->excluded_extensions
        // regex: ^.*(?<!(\.inc)|(\.php))$

        // @fixme: process $decoded_options->excluded_files
        // regex: ^(FilesHandler.php)|(GitHandler.inc)$

        // @fixme: process $decoded_options->included_files
        // regex: ?

        return $parsed_options;
    }
}