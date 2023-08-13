<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\CompoundControl;

/**
 * Interface for defining classes that validate compound form controls (e.g. a complex form control or a form).
 */
interface CompoundValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether the values of a compound form control meets the conditions of this validator.
   *
   * @param CompoundControl $control The compound form control to be validated.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function validate(CompoundControl $control): bool;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
