<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

/**
 * Cleaner for removing leading and training whitespace and replacing intermediate whitespace and multiple
 * intermediate whitespaces (including new lines and tabs) with a single space.
 */
class PruneWhitespaceCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var PruneWhitespaceCleaner|null
   */
  static private ?PruneWhitespaceCleaner $singleton = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return PruneWhitespaceCleaner
   */
  public static function get(): PruneWhitespaceCleaner
  {
    if (self::$singleton===null) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a submitted value with leading and training whitespace removed. Intermediate whitespace and multiple
   * intermediate whitespace (including new lines and tabs) are replaced with a single space.
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

    $clean = trim(mb_ereg_replace('[ \t\n\r\0\x0B]+', ' ', $value, 'p'));

    return ($clean==='') ? null : $clean;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
