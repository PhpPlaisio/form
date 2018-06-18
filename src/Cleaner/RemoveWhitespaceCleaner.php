<?php

namespace SetBased\Abc\Form\Cleaner;

/**
 * Cleaner for removing all whitespace.
 */
class RemoveWhitespaceCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var RemoveWhitespaceCleaner
   */
  static private $singleton;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return RemoveWhitespaceCleaner
   */
  public static function get(): RemoveWhitespaceCleaner
  {
    if (!self::$singleton) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a submitted value with all whitespace removed.
   *
   * @param string|null $value The submitted value.
   *
   * @return string|null
   */
  public function clean($value)
  {
    if ($value==='' || $value===null || $value===false)
    {
      return null;
    }

    $tmp = AmbiguityCleaner::get()->clean($value);

    $tmp = str_replace([' ', "\t", "\n"], '', $tmp);
    if ($tmp==='') $tmp = null;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
