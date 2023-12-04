<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Cleaner\IntegerCleaner;

/**
 * Test cases for class IntegerCleaner.
 */
class IntegerCleanerTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return HTML snippet test cases.
   *
   * @return string[][]
   */
  public static function getCases(): array
  {
    return [[null, null],
            ['', null],
            ['123', 123],
            [123, 123],
            [1.0, 1],
            [1.1, 1.1],
            [false, 0],
            [true, 1]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns non-strings.
   *
   * @return array
   */
  public static function getNonStrings(): array
  {
    return [[[]],
            [new \stdClass()],
            [fopen('php://stdin', 'r')]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test various inputs.
   *
   * @param mixed $value    The dirty value.
   * @param mixed $expected The expected clean value.
   *
   * @dataProvider getCases
   */
  public function testClean(mixed $value, mixed $expected): void
  {
    $cleaner = IntegerCleaner::get();

    $clean = $cleaner->clean($value);
    self::assertSame($expected, $clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a cleaning a non-string yields the item.
   *
   * @param mixed $value The non-string.
   *
   * @dataProvider getNonStrings
   */
  public function testNonString(mixed $value): void
  {
    $this->expectException(\LogicException::class);

    $cleaner = new IntegerCleaner();
    $cleaner->clean($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

