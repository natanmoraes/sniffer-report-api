<?php
namespace SnifferReport\Service;

class OptionsParser
{
    public static function parseOptions($options)
    {
        if (empty($options)) {
            // @fixme throw new EmptyOptionsException();
        }

        $decoded_options = json_decode($options);
        if (empty($decoded_options)) {
            // @fixme throw new UnableToParseOptionsException();
        }

        // @fixme: proccess $decoded_options->include_extensions
        // @fixme: proccess $decoded_options->exclude_extensions
        // @fixme: proccess $decoded_options->ignore_file
        // @fixme: proccess $decoded_options->include_file

        return $parsed_options;
    }
}