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
  public function testInvalidDate1(): void
  {
    $text      = '1966-11-31';
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format($text);

    self::assertEquals($text, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testInvalidDate2(): void
  {
    $text      = 'This not a date.';
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format($text);

    self::assertEquals($text, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testValidDate(): void
  {
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format('1966-04-10');

    self::assertEquals('10-04-1966', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

