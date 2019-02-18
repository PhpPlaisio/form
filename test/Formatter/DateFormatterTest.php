<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Formatter;

use SetBased\Abc\Form\Formatter\DateFormatter;
use SetBased\Abc\Form\Test\AbcTestCase;

/**
 * Test cases for class DateFormatter.
 */
class DateFormatterTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A formatter must return the original value when the value is not valid.
   */
  public function testInvalidDate1(): void
  {
    $text      = '1966-11-31';
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format($text);

    self::assertEquals($text, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A formatter must return the original value when the value is not valid.
   */
  public function testInvalidDate2(): void
  {
    $text      = 'This not a date.';
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format($text);

    self::assertEquals($text, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid date.
   */
  public function testValidDate(): void
  {
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format('1966-04-10');

    self::assertEquals('10-04-1966', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid date.
   */
  public function testEmptyDate(): void
  {
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format('');

    self::assertEquals('', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid date.
   */
  public function testNullDate(): void
  {
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format(null);

    self::assertNull($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

