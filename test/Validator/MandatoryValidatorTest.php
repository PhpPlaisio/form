<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Validator\MandatoryValidator;

/**
 * Test cases for class MandatoryValidator.
 */
class MandatoryValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid mandatory values.
   *
   * @return array
   */
  public static function getInvalidValues(): array
  {
    $ret = [];

    $ret[] = [''];
    $ret[] = [null];
    $ret[] = [false];
    $ret[] = [[]];
    $ret[] = [['foo' => ['bar' => []]]];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory values.
   *
   * @return array
   */
  public static function getValidValues(): array
  {
    $ret = [];

    $ret[] = ['Hello, World!'];
    $ret[] = [true];
    $ret[] = [0];
    $ret[] = ['0'];
    $ret[] = [new \stdClass()];
    $ret[] = [['options' => ['a' => false, 'b' => true, 'c' => false]]];
    $ret[] = [['options' => ['a' => '', 'b' => '0', 'c' => null]]];
    $ret[] = [['options' => ['a' => '', 'b' => 0, 'c' => null]]];
    $ret[] = [['options' => ['a' => '', 'b' => 'Hello, World!', 'c' => null]]];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against invalid dates.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider getInvalidValues
   */
  public function testInvalidAddresses(mixed $value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new MandatoryValidator();
    $valid     = $validator->validate($control);
    $errors    = $control->getErrorMessages();
    self::assertFalse($valid);
    self::assertIsArray($errors);
    self::assertCount(1, $errors);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against valid dates.
   *
   * @param mixed $value The valid value.
   *
   * @dataProvider getValidValues
   */
  public function testValidAddresses(mixed $value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new MandatoryValidator();
    $valid     = $validator->validate($control);
    $errors    = $control->getErrorMessages();
    self::assertTrue($valid);
    self::assertNull($errors);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
