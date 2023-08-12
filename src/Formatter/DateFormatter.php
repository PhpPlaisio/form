<?php
declare(strict_types=1);

namespace Plaisio\Form\Formatter;

/**
 * Formatter for formatting dates.
 */
class DateFormatter implements Formatter
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The format specifier, see <http://www.php.net/manual/function.date.php>.
   *
   * @var string
   */
  private string $format;

  /**
   * If set the date that will be treated as an open date. An open date will be shown as an empty form control.
   *
   * @var string|null
   */
  private ?string $openDate = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $format The date format, see <http://www.php.net/manual/function.date.php>.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(string $format = 'Y-m-d')
  {
    $this->format = $format;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If the machine value is a valid date returns the date formatted according the format specifier. Otherwise,
   * returns the machine value unchanged.
   *
   * @param mixed $value The machine value.
   *
   * @return mixed
   *
   * @since 1.0.0
   * @api
   */
  public function format(mixed $value): mixed
  {
    if ($value===null)
    {
      return null;
    }

    $match = preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $parts);
    $valid = ($match && checkdate((int)$parts[2], (int)$parts[3], (int)$parts[1]));
    if ($valid)
    {
      if ($value===$this->openDate)
      {
        return '';
      }

      $date = new \DateTime($value);

      return $date->format($this->format);
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the open date. An open date will be shown as an empty field.
   *
   * @param string|null $openDate The open date in YYYY-MM-DD format.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setOpenDate(?string $openDate): self
  {
    $this->openDate = $openDate;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
