<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\Control;

/**
 * Validator for form controls of type [input:number](http://www.w3schools.com/tags/tag_input.asp).
 */
class NumberValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control is an integer and with the specified range. Otherwise returns false.
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
    $options = ['options' => ['min_range' => $control->getAttribute('min'),
                              'max_range' => $control->getAttribute('max')]];

    $value = $control->getSubmittedValue();

    // An empty value is valid.
    if ($value==='' || $value===null || $value===false)
    {
      return true;
    }

    // Objects and arrays are not an integer.
    if (!is_scalar($value))
    {
      return false;
    }

    // Filter valid integer values with valid range.
    $integer = filter_var($value, FILTER_VALIDATE_INT, $options);

    // If the actual value and the filtered value are not equal the value is not an integer.
    if ((string)$integer!==(string)$value)
    {
      return false;
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
