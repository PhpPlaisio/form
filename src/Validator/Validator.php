<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;

/**
 * Interface for defining validators on form controls.
 */
interface Validator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control meets the conditions of this validator. Returns false otherwise.
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
