<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

use SetBased\Exception\LogicException;

/**
 * Cleaner for database labels.
 */
class DatabaseLabelCleaner implements Cleaner
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
   * Cleans the submitted value.
   *
   * @param mixed $value The submitted value.
   *
   * @return string|null
   */
  public function clean(mixed $value): ?string
  {
    if ($value==='' || $value===null)
    {
      return null;
    }

    if (!is_string($value))
    {
      throw new LogicException('Expecting a string, got a %s.', gettype($value));
    }

    $clean = $value;
    if (!str_starts_with($value, $this->prefix.'_'))
    {
      $clean = sprintf('%s_%s', $this->prefix, $clean);
    }

    return trim(preg_replace('/[^0-9A-Z]+/', '_', mb_strtoupper($clean)), '_');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
