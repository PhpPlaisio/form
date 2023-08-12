<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\Control;

/**
 * Validator for dates.
 */
class DateValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether the submitted value of a form control is a valid date.
   *
   * Note:
   * * Empty values are considered valid.
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
    if ($value===null || $value==='')
    {
      return true;
    }

    // Only a string can hold a valid value.
    if (!is_string($value))
    {
      return false;
    }

    // We assume that DateCleaner did a good job and date is in YYYY-MM-DD format.
    $match = preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $parts);
    $valid = ($match && checkdate((int)$parts[2], (int)$parts[3], (int)$parts[1]));
    if (!$valid)
    {
      $control->setErrorMessage('Invalid date');
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
