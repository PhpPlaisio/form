<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Validator\DateValidator;

/**
 * Test cases for class DateValidator.
 */
class DateValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid dates.
   *
   * @return array
   */
  public static function getInvalidValues(): array
  {
    return [['15- 7 -20'],
            ['10-april-1966'],
            ['Hello world.'],
            ['15- 7 -20'],
            ['2015-02-29'],
            ['2020-11-31'],
            [new \stdClass()],
            ['foo' => 'bar'],
            [false],
            [true]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid dates.
   *
   * @return array
   */
  public static function getValidValues(): array
  {
    return [['1966-04-10'], ['1970-01-01'], ['1900-01-01'], ['9999-12-31'], ['2020-02-29'], [''], [null]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against invalid dates.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider getInvalidValues
   */
  public function testInvalidDates(mixed $value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new DateValidator();
    $valid     = $validator->validate($control);
    self::assertFalse($valid);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against valid dates.
   *
   * @param mixed $value The valid value.
   *
   * @dataProvider getValidValues
   */
  public function testValidDates(mixed $value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new DateValidator();
    $valid     = $validator->validate($control);
    self::assertTrue($valid);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

