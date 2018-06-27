<?php

namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Helper\Html;

/**
 * Validator for validating the string length of the value of a form control.
 */
class LengthValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The upper bound of the range of valid lengths.
   *
   * @var int
   */
  private $maxLength;

  /**
   * The lower bound of the range of valid lengths.
   *
   * @var int
   */
  private $minLength;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $minLength The minimum length.
   * @param int $maxLength The maximum length.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(int $minLength = 0, int $maxLength = PHP_INT_MAX)
  {
    $this->minLength = $minLength;
    $this->maxLength = $maxLength;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the length of the value of a form control is within the specified range. Otherwise returns false.
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
    if ($value==='' || $value===null || $value===false)
    {
      return true;
    }

    // Objects and arrays are not valid.
    if (!is_scalar($value))
    {
      return false;
    }

    $length = mb_strlen($value, Html::$encoding);

    return (($this->minLength <= $length) && ($length <= $this->maxLength));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
