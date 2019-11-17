<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\Control;

/**
 * Validates if a form control has a value. Can be applied on any form control object.
 */
class MandatoryValidator implements Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the form control has a value.
   *
   * Note:
   * * Empty values are considered invalid.
   * * If the form control is a complex form control all child form control must have a value.
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

    if ($value==='' || $value===null || $value===false)
    {
      return false;
    }

    if (is_array($value))
    {
      return $this->validateArray($value);
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates recursively if one of the leaves has a value.
   *
   * @param array $array
   *
   * @return bool
   */
  private function validateArray(array $array): bool
  {
    foreach ($array as $element)
    {
      if (is_array($element))
      {
        $tmp = $this->validateArray($element);
        if ($tmp===true)
        {
          return true;
        }
      }
      else
      {
        if ($element!==null && $element!==false && $element!=='')
        {
          return true;
        }
      }
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
