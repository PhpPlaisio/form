<?php
//--------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;
use SetBased\Exception\LogicException;

/**
 * Validator for dates.
 */
class DateValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control is a valid date. Otherwise returns false.
   *
   * Note:
   * * Empty values are considered valid.
   *
   * @param Control $control The DateControl.
   *
   * @return bool
   */
  public function validate(Control $control): bool
  {
    $value = $control->getSubmittedValue();

    // An empty value is valid.
    if ($value===null || $value===false || $value==='') return true;

    // Objects and arrays are not valid dates.
    if (!is_scalar($value)) throw new LogicException('%s is not a valid date.', gettype($value));

    // We assume that DateCleaner did a good job and date is in YYYY-MM-DD format.
    $match = preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $parts);
    $valid = ($match && checkdate($parts[2], $parts[3], $parts[1]));
    if (!$valid)
    {
      // @todo babel
      $message = sprintf("'%s' is geen geldige datum.", $control->getSubmittedValue());
      $control->setErrorMessage($message);
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
