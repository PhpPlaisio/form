<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

/**
 * Cleaner for removing leading and training whitespace.
 */
class TrimWhitespaceCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var TrimWhitespaceCleaner|null
   */
  static private ?TrimWhitespaceCleaner $singleton = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return TrimWhitespaceCleaner
   */
  public static function get(): TrimWhitespaceCleaner
  {
    if (self::$singleton===null) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a submitted value with leading and training whitespace removed.
   *
   * @param mixed $value The submitted value.
   *
   * @return mixed
   *
   * @since 1.0.0
   * @api
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

    $clean = trim($value);

    return ($clean==='') ? null : $clean;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
