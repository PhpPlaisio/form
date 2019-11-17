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
   * @var TrimWhitespaceCleaner
   */
  static private $singleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return TrimWhitespaceCleaner
   */
  public static function get(): TrimWhitespaceCleaner
  {
    if (!self::$singleton) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a submitted value with leading and training whitespace removed.
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

    $tmp = trim($tmp, " \t\n");
    if ($tmp==='') $tmp = null;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
