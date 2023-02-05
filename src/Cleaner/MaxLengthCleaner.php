<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

use SetBased\Exception\LogicException;

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

    return mb_substr($value, 0, $this->maxLength);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
