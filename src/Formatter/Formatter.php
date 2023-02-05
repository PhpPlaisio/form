<?php
declare(strict_types=1);

namespace Plaisio\Form\Formatter;

/**
 * Interface for defining classes for formatting values from machine values the human-readable values.
 */
interface Formatter
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the human-readable value of a machine value.
   *
   * @param mixed $value The machine value.
   *
   * @return mixed
   *
   * @since 1.0.0
   * @api
   */
  public function format(mixed $value): mixed;
}

//----------------------------------------------------------------------------------------------------------------------
