<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Validator\LengthValidator;

/**
 * Test cases for class LengthValidator.
 */
class LengthValidatorTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid string.
   *
   * @return array
   */
  public function getInvalidValues(): array
  {
    $ret = [];

    // A too long string.
    $ret[] = [10, 20, 'Hyperbolicsyllabicsesquedalymistic'];

    // a too short string.
    $ret[] = [10, 20, 'Isaac'];

    // Only strings are valid
    $ret[] = [0, 100, $this];
    $ret[] = [0, 100, ['foo' => 'bar']];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid strings.
   *
   * @return array
   */
  public function getValidValues(): array
  {
    $ret = [];

    // A string with minimum length.
    $ret[] = [3, 8, 'hot'];

    // A string with maximum length.
    $ret[] = [3, 8, 'buttered'];

    // A string between minn and max length.
    $ret[] = [3, 8, 'soul'];

    // An empty string is valid.
    $ret[] = [0, 100, ''];
    $ret[] = [0, 100, null];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against invalid strings.
   *
   * @param int   $minLength The minimum length.
   * @param int   $maxLength The maximum length.
   * @param mixed $value     The invalid value.
   *
   * @dataProvider getInvalidValues
   */
  public function testInvalidStrings(int $minLength, int $maxLength, $value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new LengthValidator($minLength, $maxLength);
    $valid     = $validator->validate($control);
    self::assertFalse($valid);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against valid strings.
   *
   * @param int   $minLength The minimum length.
   * @param int   $maxLength The maximum length.
   * @param mixed $value     The valid value.
   *
   * @dataProvider getValidValues
   */
  public function testValidStrings(int $minLength, int $maxLength, $value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new LengthValidator($minLength, $maxLength);
    $valid     = $validator->validate($control);
    self::assertTrue($valid);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
