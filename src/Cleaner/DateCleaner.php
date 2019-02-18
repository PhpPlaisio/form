<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Cleaner;

/**
 * Cleaner for cleaning and transforming dates to ISO 8601 machine format.
 */
class DateCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The alternative separators in the format of this validator.
   *
   * @var string|null
   */
  protected $alternativeSeparators;

  /**
   * The expected format of the date.
   *
   * @var string
   */
  protected $format;

  /**
   * The expected separator in the format of this validator.
   *
   * @var string|null
   */
  protected $separator;

  /**
   * If set the date that will treated as an open date. An empty form control will be translated to the open date.
   *
   * @var string|null
   */
  private $openDate;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string      $format                The expected date format. See
   *                                           DateTime::createFromFormat](http://php.net/manual/datetime.createfromformat.php)
   *                                           for the formatting options.
   * @param string|null $separator             The separator (a single character) in the expected format.
   * @param string|null $alternativeSeparators Alternative separators (each character is an alternative separator).
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(string $format, ?string $separator = null, ?string $alternativeSeparators = null)
  {
    $this->format                = $format;
    $this->separator             = $separator;
    $this->alternativeSeparators = $alternativeSeparators;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Cleans a submitted date and returns the date in ISO 8601 machine format if the date is a valid date. Otherwise
   * returns the original submitted value.
   *
   * @param string|null $value The submitted date.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
   */
  public function clean($value)
  {
    // First prune whitespace.
    $value = PruneWhitespaceCleaner::get()->clean($value);

    // If the value is empty return immediately.
    if ($value==='' || $value===null || $value===false)
    {
      return $this->openDate;
    }

    // First validate against ISO 8601.
    $match = preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $parts);
    $valid = ($match && checkdate((int)$parts[2], (int)$parts[3], (int)$parts[1]));
    if ($valid)
    {
      $date = new \DateTime($value);

      return $date->format('Y-m-d');
    }

    // Replace alternative separators with the expected separator.
    if ($this->separator!==null && $this->alternativeSeparators!==null)
    {
      $value = strtr($value,
                     $this->alternativeSeparators,
                     str_repeat($this->separator[0], strlen($this->alternativeSeparators)));
    }

    // Validate against $format.
    $date = \DateTime::createFromFormat($this->format, $value);
    if ($date)
    {
      // Note: String '2000-02-30' will transformed to date '2000-03-01' with a warning. We consider this as an
      // invalid date.
      $tmp = $date::getLastErrors();
      if ($tmp['warning_count']==0) return $date->format('Y-m-d');
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the open date. An empty submitted value will be replaced with the open date.
   *
   * @param string $openDate The open date in YYYY-MM-DD format.
   *
   * @since 1.0.0
   * @api
   */
  public function setOpenDate(?string $openDate): void
  {
    $this->openDate = $openDate;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
