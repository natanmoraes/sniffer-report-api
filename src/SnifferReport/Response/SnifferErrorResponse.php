<?php

namespace SnifferReport\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class SnifferErrorResponse extends JsonResponse {

  /**
   * SnifferErrorResponse constructor.
   *
   * Defines a standard way of returning an error response in the app.
   *
   * @param string $message
   * @param int $code
   */
  public function __construct($message, $code = 500) {

    $data = [
      'status' => 'error',
      'code' => $code,
      'message' => $message,
    ];

    return parent::__construct(json_encode($data), $code, [], TRUE);
  }
}
