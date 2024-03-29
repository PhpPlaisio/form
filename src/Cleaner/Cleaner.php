<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

/**
 * Interface for defining classes for cleaning submitted values and translating formatted values to machine values.
 */
interface Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Cleans a submitted value.
   *
   * @param mixed $value The submitted value.
   *
   * @return mixed The cleaned submitted value.
   *
   * @since 1.0.0
   * @api
   */
  public function clean(mixed $value): mixed;
}

//----------------------------------------------------------------------------------------------------------------------
