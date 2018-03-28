<?php

namespace Shiyan\Iterate\Exception;

/**
 * Exception thrown in case of PCRE regex execution error.
 */
class PregLastError extends \Exception {

  /**
   * Constructs a PregLastError exception.
   */
  public function __construct() {
    parent::__construct('PCRE regex execution error.', preg_last_error());
  }

}
