<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\Control;

/**
 * Validator for integer values.
 */
class IntegerValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The possible error messages.
   *
   * @var array[]
   */
  public static array $errors = [['min'     => PHP_INT_MIN,
                                  'max'     => PHP_INT_MAX,
                                  'message' => 'A whole number is expected.'],
                                 ['min'     => null,
                                  'max'     => PHP_INT_MAX,
                                  'message' => 'A whole number equal or larger than %1$d expected'],
                                 ['min'     => PHP_INT_MIN,
                                  'max'     => null,
                                  'message' => 'A whole number equal or smaller than %1$d expected'],
                                 ['min'     => null,
                                  'max'     => null,
                                  'message' => 'A whole number between %1$d and %2$d expected']];

  /**
   * The upper bound of the range of valid integer value
   *
   * @var int
   */
  private int $maxValue;

  /**
   * The lower bound of the range of valid integer value.
   *
   * @var int
   */
  private int $minValue;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int|null $minValue The minimum required value.
   * @param int|null $maxValue The maximum required value.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?int $minValue = null, ?int $maxValue = null)
  {
    $this->minValue = $minValue ?? PHP_INT_MIN;
    $this->maxValue = $maxValue ?? PHP_INT_MAX;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the maximum value.
   *
   * @param int|null $maxValue The maximum value.
   *
   * @return IntegerValidator
   */
  public function setMaxValue(?int $maxValue): IntegerValidator
  {
    $this->maxValue = $maxValue ?? PHP_INT_MAX;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the minimum value.
   *
   * @param int|null $minValue The minimum value.
   *
   * @return IntegerValidator
   */
  public function setMinValue(?int $minValue): IntegerValidator
  {
    $this->minValue = $minValue ?? PHP_INT_MIN;

    return $this;
  }

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
    $options = ['options' => ['min_range' => $this->minValue,
                              'max_range' => $this->maxValue]];

    $value = $control->getSubmittedValue();

    // An empty value is valid.
    if ($value==='' || $value===null)
    {
      return true;
    }

    // Only strings and numbers can be valid values.
    if (!is_string($value) && !is_numeric($value))
    {
      $control->setErrorMessage('Not a whole number.');

      return false;
    }

    // Filter valid integer values with valid range.
    $integer = filter_var($value, FILTER_VALIDATE_INT, $options);

    // If the actual value and the filtered value are not equal the value is not an integer.
    if ((string)$integer!==(string)$value)
    {
      $control->setErrorMessage($this->getErrorMessage());

      return false;
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the appropriate error message.
   *
   * @return string
   */
  private function getErrorMessage(): string
  {
    $minValue = ($this->minValue!==PHP_INT_MIN) ? null : $this->minValue;
    $maxValue = ($this->maxValue!==PHP_INT_MAX) ? null : $this->maxValue;

    foreach (self::$errors as $error)
    {
      if ($error['min']===$minValue && $error['max']===$maxValue)
      {
        return $error['message'];
      }
    }

    throw new \LogicException('Can not find error.');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
