<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Validator\EmailValidator;

/**
 * Test cases for class DateValidator.
 */
class EmailValidatorTest extends PlaisioTestCase
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

    // A strange but valid email address must be valid.
    $ret[] = ['"much.more unusual"@setbased.nl'];
    $ret[] = ['"very.unusual.@.unusual.com"@setbased.nl'];

    // Localhost is not a valid domain part.
    $ret[] = ['info@localhost'];

    // Only one @ is allowed outside quotation marks.
    $ret[] = ['info@info@setbased.nl'];
    $ret[] = ['info@setbased.nl@info'];

    // An email address without an existing A or MX record is invalid.
    $ret[] = ['info@xsetbased.nl'];

    // An email address with a to long local part must be invalid. The maximum length of the local part is 64 characters,
    // see http://en.wikipedia.org/wiki/Email_address.
    $local = str_repeat('x', 65);
    $ret[] = ["$local@setbased.nl"];

    // An @ character must separate local and domain part.
    $ret[] = ['info.setbased.nl'];

    // Only strings are valid
    $ret[] = [$this];
    $ret[] = [['foo' => 'bar']];
    $ret[] = [false];
    $ret[] = [true];

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

    // A strange but valid email address must be valid.
    $ret[] = ['!#$%&\'*+-/=?^_`{}|~@setbased.nl'];

    // A valid email address must be valid.
    $ret[] = ['info@setbased.nl'];
    $ret[] = ['p.r.water@setbased.nl'];
    $ret[] = ['disposable.style.email.with+symbol@setbased.nl'];

    // An email address with a long domain part must be valid. The maximum length of the domain part is 255 characters,
    // see http://en.wikipedia.org/wiki/Email_address.
    $ret[] = ['info@thelongestdomainnameintheworldandthensomeandthensomemoreandmore.com'];

    // An email address with a long local part must be valid. The maximum length of the local part is 64 characters,
    // see http://en.wikipedia.org/wiki/Email_address.
    $local = str_repeat('x', 64);
    $ret[] = ["$local@setbased.nl"];

    // An empty email address is a valid email address.
    $ret[] = [''];
    $ret[] = [null];

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
  public function testInvalidAddresses($value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new EmailValidator();
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
  public function testValidAddresses($value): void
  {
    $control   = new TestControl('test', $value);
    $validator = new EmailValidator();
    $valid     = $validator->validate($control);
    self::assertTrue($valid);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
