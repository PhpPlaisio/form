<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Validator\IntegerValidator;

/**
 * Test cases for class IntegerValidator.
 */
class IntegerValidatorTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid integers.
   *
   * @return array
   */
  public static function getInvalidValues(): array
  {
    $ret = [];

    // Not integers.
    $ret[] = [null, null, 'string'];
    $ret[] = [null, null, '123abc'];
    $ret[] = [null, null, '0.1'];
    $ret[] = [null, null, 1.2];
    $ret[] = [null, null, pi()];
    $ret[] = [null, null, false];
    $ret[] = [null, null, true];

    // Out of range.
    $ret[] = [-1, 10, -9];
    $ret[] = [-1, 10, -2];
    $ret[] = [-1, 10, -23];

    // Only strings or integers are valid.
    $ret[] = [null, null, new \stdClass()];
    $ret[] = [null, null, ['foo' => 'bar']];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid integers.
   *
   * @return array
   */
  public static function getValidValues(): array
  {
    $ret = [];

    // Integer in range.
    $ret[] = [-1, 10, -1];
    $ret[] = [-1, 10, 10];
    $ret[] = [-1, 10, 5];
    $ret[] = [-1, 10, 0];

    // String in range.
    $ret[] = [-1, 10, '-1'];
    $ret[] = [-1, 10, '10'];
    $ret[] = [-1, 10, '5'];
    $ret[] = [-1, 10, '0'];

    // No range.
    $ret[] = [null, null, '0'];
    $ret[] = [null, null, 0];
    $ret[] = [null, null, PHP_INT_MIN];
    $ret[] = [null, null, PHP_INT_MAX];

    // An empty string is valid.
    $ret[] = [null, null, ''];
    $ret[] = [null, null, null];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against valid strings.
   *
   * @param int|null $minValue The minimum value.
   * @param int|null $maxValue The maximum value.
   * @param mixed    $value    The valid value.
   *
   * @dataProvider getValidValues
   */
  public static function testValidValues(?int $minValue, ?int $maxValue, mixed $value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new IntegerValidator($minValue, $maxValue);
    $valid     = $validator->validate($control);
    $errors    = $control->getErrorMessages();
    self::assertTrue($valid);
    self::assertNull($errors);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against invalid strings.
   *
   * @param int|null $minValue The minimum value.
   * @param int|null $maxValue The maximum value.
   * @param mixed    $value    The invalid value.
   *
   * @dataProvider getInvalidValues
   */
  public function testInvalidValues(?int $minValue, ?int $maxValue, mixed $value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new IntegerValidator($minValue, $maxValue);
    $valid     = $validator->validate($control);
    $errors    = $control->getErrorMessages();
    self::assertFalse($valid);
    self::assertIsArray($errors);
    self::assertCount(1, $errors);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
