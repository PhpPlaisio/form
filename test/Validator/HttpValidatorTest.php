<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Validator\HttpValidator;

/**
 * Test cases for class HttpValidator.
 */
class HttpValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid email addresses.
   *
   * @return array
   */
  public function getInvalidValues(): array
  {
    $ret = [];

    // An usual url address must be invalid.
    $ret[] = ['hffd//:www.setbased/nl'];
    $ret[] = ['http//golgelinva'];
    $ret[] = ['ftp//:!#$%&\'*+-/=?^_`{}|~ed.com'];

    // Only HTTP protocol is valid.
    $ret[] = ['ftp://setbased.nl'];

    // Website must exists.
    $ret[] = ['http://www.xsetbased.nl'];

    // Only strings are valid
    $ret[] = [$this];
    $ret[] = [['foo' => 'bar']];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid email addresses.
   *
   * @return array
   */
  public function getValidValues(): array
  {
    $ret = [];

    // Valid URL must be valid.
    $ret[] = ['http://www.setbased.nl'];
    $ret[] = ['http://www.google.com'];
    $ret[] = ['http://www.php.net'];
    $ret[] = ['https://www.google.com'];
    $ret[] = ['https://www.php.net'];

    // An empty URL is valid.
    $ret[] = [''];
    $ret[] = [null];

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against invalid URLs.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider getInvalidValues
   */
  public function testInvalidUrl($value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new HttpValidator();
    $valid     = $validator->validate($control);
    self::assertFalse($valid);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against valid URLs.
   *
   * @param mixed $value The valid value.
   *
   * @dataProvider getValidValues
   */
  public function testValidUrl($value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new HttpValidator();
    $valid     = $validator->validate($control);
    self::assertTrue($valid);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
