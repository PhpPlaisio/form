<?php

namespace SetBased\Abc\Form\Cleaner;

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
   */
  public function __construct($maxLength)
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
   */
  public function clean($value)
  {
    if ($value==='' || $value===null || $value===false)
    {
      return null;
    }

    return mb_substr($value, 0, $this->maxLength);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
