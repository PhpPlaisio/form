<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

use SetBased\Helper\Cast;

/**
 * Cleaner casting a submitted value to an integer.
 */
class IntegerCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var IntegerCleaner|null
   */
  static private ?IntegerCleaner $singleton = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return IntegerCleaner
   */
  public static function get(): IntegerCleaner
  {
    if (self::$singleton===null) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a submitted value casted to an integer.
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
    // Return null for empty values.
    if ($value==='' || $value===null)
    {
      return null;
    }

    // Return original value for non-integers.
    if (!Cast::isManInt($value))
    {
      return $value;
    }

    return Cast::toManInt($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
