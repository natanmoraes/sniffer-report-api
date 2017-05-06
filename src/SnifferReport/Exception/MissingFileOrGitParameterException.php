<?php

namespace SnifferReport\Exception;

class MissingFileOrGitParameterException extends SnifferReportException
{
    public function __construct()
    {
        parent::__construct(
            'File or Git URL required',
            400
        );
    }
}
