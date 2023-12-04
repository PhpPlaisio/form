<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\Control;

/**
 * Validator for http URLs.
 */
class HttpValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether the value of a form control is valid HTTP address.
   *
   * Note:
   * * Empty values are considered valid.
   * * This validator will test if the URL actually exists.
   *
   * @param Control $control The form control.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function validate(Control $control): bool
  {
    $value = $control->getSubmittedValue();

    // An empty value is valid.
    if ($value==='' || $value===null)
    {
      return true;
    }

    // Only strings can hold a valid value.
    if (!is_string($value))
    {
      return false;
    }

    // Filter valid URL from the value.
    $url = filter_var($value, FILTER_VALIDATE_URL);

    // If the actual value and the filtered value are not equal the value is not a valid url.
    if ($url!==$value)
    {
      return false;
    }

    // filter_var allows not to specify the HTTP protocol. Test the URL starts with http or https.
    if (!str_starts_with($value, 'http:') && !str_starts_with($value, 'https:'))
    {
      return false;
    }

    // Test that the page actually exits. We consider all HTTP 200-399 responses as valid.
    try
    {
      $headers = @get_headers($url);
      $valid   = (is_array($headers) && preg_match('/^HTTP\\/\\d+\\.\\d+\\s+[23]\\d\\d\\s*.*$/', $headers[0]));
    }
    catch (\Exception $e)
    {
      // Something went wrong. Possibly:
      // * Unable to open stream.
      // * Domain does not exist.
      // * Domain does have a website.
      // * Peer certificate did not match expected.
      $valid = false;
    }

    if (!$valid)
    {
      $control->setErrorMessage('Voer een geldige website in.');
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
