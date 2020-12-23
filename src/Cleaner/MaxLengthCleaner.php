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
  private int $maxLength;

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

    return mb_substr($value, 0, $this->maxLength);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
