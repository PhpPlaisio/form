<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

use SetBased\Exception\LogicException;

/**
 * Cleaner for removing all whitespace.
 */
class RemoveWhitespaceCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var RemoveWhitespaceCleaner|null
   */
  static private ?RemoveWhitespaceCleaner $singleton = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return RemoveWhitespaceCleaner
   */
  public static function get(): RemoveWhitespaceCleaner
  {
    if (!self::$singleton)
    {
      self::$singleton = new self();
    }

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a submitted value with all whitespace removed.
   *
   * @param mixed $value The submitted value.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
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

    $clean = str_replace([' ', "\t", "\n"], '', $value);

    return ($clean==='') ? null : $clean;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
