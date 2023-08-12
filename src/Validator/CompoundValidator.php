<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\CompoundControl;

/**
 * Interface for defining classes that validate compound controls (e.g. a complex control or a form).
 */
interface CompoundValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether the values of a compound control meets the conditions of this validator.
   *
   * @param CompoundControl $control The compound control to be validated.
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
