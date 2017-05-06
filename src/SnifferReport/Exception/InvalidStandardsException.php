<?php
namespace SnifferReport\Exception;

class InvalidStandardsException extends SnifferReportException
{
    public function __construct()
    {
        parent::__construct(
            'One or more standards are not valid. Please try again with different values',
            400
        );
    }
}
