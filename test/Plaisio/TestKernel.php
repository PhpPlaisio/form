<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Plaisio;

use Plaisio\PlaisioKernel;
use Plaisio\Request\CoreRequest;
use Plaisio\Request\Request;

/**
 * Kernel for testing purposes.
 */
class TestKernel extends PlaisioKernel
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the helper object for providing information about the HTTP request.
   *
   * @return Request
   */
  protected function getRequest(): Request
  {
    $request = new CoreRequest($_SERVER, $_GET, $_POST, $_COOKIE, new TestRequestParameterResolver());
    $request->validate();

    return $request;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
