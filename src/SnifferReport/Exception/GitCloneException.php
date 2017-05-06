<?php

namespace SnifferReport\Exception;

class GitCloneException extends SnifferReportException
{
    public function __construct(\Exception $e)
    {
        parent::__construct(
            "Error when trying to clone git repository: {$e->getMessage()}",
            500
        );
    }
}
