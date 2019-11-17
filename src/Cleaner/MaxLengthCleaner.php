<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

/**
 * Cleaner for trimming down the length of string to a maximum.
 */
class MaxLengthCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The maximum length.
   *
   * @var int
   */
  private $maxLength;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $maxLength The maximum length.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(int $maxLength)
  {
    $this->maxLength = $maxLength;
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

    $tmp = PruneWhitespaceCleaner::get()->clean($value);
    if ($tmp==='' || $tmp===null)
    {
      return null;
    }

    return mb_substr($tmp, 0, $this->maxLength);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
