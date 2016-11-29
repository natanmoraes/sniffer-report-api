<?php

namespace SnifferReport\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use SnifferReport\Model\Sniff;

class SnifferSuccessResponse extends JsonResponse {

  /**
   * SnifferSuccessResponse constructor.
   *
   * Defines a standard way of returning a success response in the app.
   *
   * @param Sniff $sniff
   * @param int $code
   */
  public function __construct(Sniff $sniff, $code = 200) {

    $data = [
      'status' => 'success',
      'code' => $code,
      'sniff' => $sniff,
    ];

    return parent::__construct(json_encode($data), $code, [], TRUE);
  }
}
