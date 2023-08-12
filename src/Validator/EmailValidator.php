<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\Control;

/**
 * Validator for email addresses.
 */
class EmailValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether the submitted value of a form control is a valid email address.
   *
   * Note:
   * * Empty values are considered valid.
   * * This validator will test if the domain exists.
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

    // Filter valid email address from the value.
    $email = filter_var($value, FILTER_VALIDATE_EMAIL);

    // If the actual value and the filtered value are not equal the value is not a valid email address.
    if ($email!==$value)
    {
      return false;
    }

    // The domain must have an MX or A record.
    $domain = substr(strstr($value, '@'), 1);
    if (!(checkdnsrr($domain.'.', 'MX') || checkdnsrr($domain.'.', 'A')))
    {
      return false;
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
