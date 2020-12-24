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
  public function getCases(): array
  {
    return [[null, null],
            ['', null],
            ['123', 123],
            [123, 123],
            [1.0, 1],
            [1.1, 1.1],
            [[], []],
            [$this, $this],
            [false, 0],
            [true, 1]];
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
  public function testClean($value, $expected): void
  {
    $cleaner = IntegerCleaner::get();

    $clean = $cleaner->clean($value);
    self::assertSame($expected, $clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

