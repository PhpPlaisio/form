<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Validator\DatabaseLabelValidator;

/**
 * Test cases for class DatabaseLabelValidator.
 */
class DatabaseLabelValidatorTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Empty values are valid.
   */
  public function testEmptyValid(): void
  {
    $validator = new DatabaseLabelValidator('CMP_ID');

    $control = new TestControl('test', null);
    self::assertTrue($validator->validate($control));

    $control = new TestControl('test', '');
    self::assertTrue($validator->validate($control));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only [0-9A-Z_]+ are allowed.
   */
  public function testNonValid(): void
  {
    $validator = new DatabaseLabelValidator('CMP_ID');

    $control = new TestControl('test', ' ');
    self::assertFalse($validator->validate($control));

    $control = new TestControl('test', 'Hello, World');
    self::assertFalse($validator->validate($control));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Label starts with prefix.
   */
  public function testPrefix(): void
  {
    $validator = new DatabaseLabelValidator('CMP_ID');

    $control = new TestControl('test', 'SYS');
    self::assertFalse($validator->validate($control));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Valid label yields true.
   */
  public function testValid(): void
  {
    $validator = new DatabaseLabelValidator('CMP_ID');

    $control = new TestControl('test', 'CMP_ID_SYS');
    self::assertTrue($validator->validate($control));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
