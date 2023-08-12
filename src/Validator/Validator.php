<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\Control;

/**
 * Interface for defining validators on form controls.
 */
interface Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether the value of a form control meets the conditions of this validator.
   *
   * @param Control $control The form control.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function validate(Control $control): bool;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
