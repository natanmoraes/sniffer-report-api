<?php
namespace SnifferReport\Exception;

class UnableToParseOptionsException extends SnifferReportException
{
    public function __construct()
    {
        parent::__construct(
            'Unable to parse options',
            400
        );
    }
}
