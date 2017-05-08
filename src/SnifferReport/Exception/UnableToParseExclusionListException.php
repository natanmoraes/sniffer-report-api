<?php

namespace SnifferReport\Exception;

class UnableToParseExclusionListException extends SnifferReportException
{
    public function __construct()
    {
        parent::__construct(
            'Unable to parse exclusion list',
            400
        );
    }
}
