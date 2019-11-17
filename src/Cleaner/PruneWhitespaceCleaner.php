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
   * @var PruneWhitespaceCleaner
   */
  static private $singleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return PruneWhitespaceCleaner
   */
  public static function get(): PruneWhitespaceCleaner
  {
    if (!self::$singleton) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a submitted value with leading and training whitespace removed. Intermediate whitespace and multiple
   * intermediate whitespace (including new lines and tabs) are replaced with a single space.
   *
   * @param string|null $value The submitted value.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
   */
  public function clean($value)
  {
    if ($value==='' || $value===null || $value===false)
    {
      return null;
    }

    $tmp = AmbiguityCleaner::get()->clean($value);

    $tmp = trim(mb_ereg_replace('[\ \t\n]+', ' ', $tmp, 'p'));
    if ($tmp==='') $tmp = null;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
