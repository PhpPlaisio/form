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
   * Validates a compound control (e.g. a complex control or a form).
   *
   * @param CompoundControl $control The compound control to be validated.
   *
   * @return bool On Successful validation returns true, otherwise false.
   *
   * @since 1.0.0
   * @api
   */
  public function validate(CompoundControl $control): bool;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
