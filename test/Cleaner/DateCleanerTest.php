<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\DateCleaner;
use Plaisio\Form\Test\Cleaner\Traits\StringCleaner;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Test cases for class DateCleaner.
 */
class DateCleanerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use StringCleaner;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of DateCleaner.
   *
   * @return DateCleaner
   */
  public function createCleaner(): DateCleaner
  {
    return new DateCleaner('d-m-Y');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against ISO 8601 format.
   */
  public function testClean1(): void
  {
    // Test ISO 8601.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = '1966-04-10';
    $value   = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against illegal dates.
   */
  public function testClean10(): void
  {
    // String with 3 parts.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = '10-april-1966';
    $value   = $cleaner->clean($raw);
    self::assertEquals('10-april-1966', $value);

    // Some other string.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = 'Hello world.';
    $value   = $cleaner->clean($raw);
    self::assertEquals('Hello world.', $value);

    // Some other string.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = '15- 7 -20';
    $value   = $cleaner->clean($raw);
    self::assertEquals('15- 7 -20', $value);

    // Some date long time ago.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = '08-11-20';
    $value   = $cleaner->clean($raw);
    self::assertEquals('0020-11-08', $value);

    // Some date long time ago.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = '08-11-020';
    $value   = $cleaner->clean($raw);
    self::assertEquals('0020-11-08', $value);

    // Some date long time ago.
    $cleaner = new DateCleaner('d-m-Y');
    $raw     = '08-11-0020';
    $value   = $cleaner->clean($raw);
    self::assertEquals('0020-11-08', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against Dutch format 'd-m-Y'.
   */
  public function testClean2(): void
  {
    $cleaner = new DateCleaner('d-m-Y');

    // Test against ISO 8601.
    $raw   = '1966-04-10';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value);

    // Test against format.
    $raw   = '10-04-1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against English format 'm/d/Y'.
   */
  public function testClean3(): void
  {
    $cleaner = new DateCleaner('m/d/Y');

    // Test against ISO 8601.
    $raw   = '1966-04-10';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value);

    // Test against format.
    $raw   = '04/10/1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value);

    // Test against format.
    $raw   = '4/10/1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against English format 'm/d/Y' with alternative separators.
   */
  public function testClean4(): void
  {
    $cleaner = new DateCleaner('m-d-Y', '-', '/-. ');

    // Test against ISO 8601.
    $raw   = '1966-04-10';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '04-10-1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '4-10-1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '04.10-1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '4 10 1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value, "Raw: $raw");

    // Test against format.
    $raw   = '4 10.1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('1966-04-10', $value, "Raw: $raw");
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against Dutch format 'd-m-Y' with illegal date, alternative separators and whitespaceOnly.
   */
  public function testClean5(): void
  {
    $cleaner = new DateCleaner('m/d/Y', '-', '/-. ');

    // Test against format.
    $raw   = '31/11.1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('31-11-1966', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against English format 'm/d/Y' with illegal date and alternative separators.
   */
  public function testClean6(): void
  {
    $cleaner = new DateCleaner('m/d/Y', '/', '/-. ');

    // Test against format.
    $raw   = '11/31/1966';
    $value = $cleaner->clean($raw);
    self::assertEquals('11/31/1966', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
