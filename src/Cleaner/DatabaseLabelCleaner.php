<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

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
   * @return mixed
   */
  public function clean($value)
  {
    // Return null for empty strings.
    if ($value==='' || $value===null)
    {
      return null;
    }

    // Return original value for non-strings.
    if (!is_string($value))
    {
      return $value;
    }

    $clean = $value;
    if (!str_starts_with($value, $this->prefix.'_'))
    {
      $clean = sprintf('%s_%s', $this->prefix, $clean);
    }

    $clean = trim(preg_replace('/[^0-9A-Z]+/', '_', mb_strtoupper($clean)), '_');

    return $clean;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
