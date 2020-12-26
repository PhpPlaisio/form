<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\Control;

/**
 * Validator for database labels.
 */
class DatabaseLabelValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The required prefix of the database label without trailing underscore.
   *
   * @var string
   */
  private string $prefix;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $prefix The required prefix of the database label without trailing underscore, e.g. CMP_ID.
   */
  public function __construct(string $prefix)
  {
    $this->prefix = $prefix;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param Control $control
   *
   * @return bool
   */
  public function validate(Control $control): bool
  {
    $value = $control->getSubmittedValue();

    // An empty value is valid.
    if ($value===null || $value==='')
    {
      return true;
    }

    // Only a string can hold a date.
    if (!is_string($value))
    {
      return false;
    }

    $pattern = sprintf('/^%s_[0-9A-Z_]+/', preg_quote($this->prefix));
    $valid   = (preg_match($pattern, $value)===1);
    if (!$valid)
    {
      $control->setErrorMessage(sprintf('Label must match pattern %s.', $pattern));
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
