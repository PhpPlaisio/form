<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\Control;
use Plaisio\Helper\Html;

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
  private int $maxLength;

  /**
   * The lower bound of the range of valid lengths.
   *
   * @var int
   */
  private int $minLength;

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
   * Returns whether the length of the value of a form control is within the specified range.
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
    if ($value==='' || $value===null)
    {
      return true;
    }

    // Only strings are valid.
    if (!is_string($value))
    {
      return false;
    }

    $length = mb_strlen($value, Html::$encoding);

    return (($this->minLength<=$length) && ($length<=$this->maxLength));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
