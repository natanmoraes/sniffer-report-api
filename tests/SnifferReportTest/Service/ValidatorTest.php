<?php

namespace SnifferReportTest\Service;

use PHPUnit\Framework\TestCase;
use SnifferReport\Service\Validator;

class ValidatorTest extends TestCase {
  public function testIsGitUrl() {
    $url = 'https://github.com/natanmoraes/sniffer-report-api.git';
    $this->assertTrue(Validator::isGitUrl($url));
  }
}