<?php

namespace SetBased\Abc\Form\Test\Formatter;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Form\Formatter\DateFormatter;

/**
 * Test cases for class DateFormatter.
 */
class DateFormatterTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testInvalidDate1()
  {
    $text      = '1966-11-31';
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format($text);

    self::assertEquals($text, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testInvalidDate2()
  {
    $text      = 'This not a date.';
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format($text);

    self::assertEquals($text, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testValidDate()
  {
    $formatter = new DateFormatter('d-m-Y');
    $value     = $formatter->format('1966-04-10');

    self::assertEquals('10-04-1966', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

