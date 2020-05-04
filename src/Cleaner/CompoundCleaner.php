<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

/**
 * Interface for defining classes for cleaning submitted values and translating formatted values to machine values.
 */
interface CompoundCleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Cleans submitted values.
   *
   * @param array $values The submitted values.
   *
   * @return array The cleaned submitted values.
   *
   * @since 1.0.0
   * @api
   */
  public function clean(array $values): array;
}

//----------------------------------------------------------------------------------------------------------------------
