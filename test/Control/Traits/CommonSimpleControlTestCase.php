<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control\Traits;

/**
 * Test cases for child classes of CommonSimpleControlTestCase.
 */
trait CommonSimpleControlTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all boolean valued attributes.
   *
   * @return string[][]
   */
  public static function booleanAttributes(): array
  {
    return [['setAttrAutoComplete', 'autocomplete'],
            ['setAttrAutoFocus', 'autofocus'],
            ['setAttrDisabled', 'disabled'],
            ['setAttrMax', 'max'],
            ['setAttrMin', 'min'],
            ['setAttrReadOnly', 'readonly'],
            ['setAttrRequired', 'required'],
            ['setAttrStep', 'step']];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all integer valued attributes.
   *
   * @return string[][]
   */
  public static function integerAttributes(): array
  {
    return [['setAttrMaxLength', 'maxlength'],
            ['setAttrSize', 'size']];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all string valued attributes.
   *
   * @return string[][]
   */
  public static function stringAttributes(): array
  {
    return [['setAttrForm', 'form'],
            ['setAttrList', 'list'],
            ['setAttrPattern', 'pattern'],
            ['setAttrPlaceHolder', 'placeholder']];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a boolean valued attribute.
   *
   * @param string $method
   * @param string $attribute
   *
   * @dataProvider booleanAttributes
   */
  public function testBoolean(string $method, string $attribute): void
  {
    $input = $this->createControl('awesome');
    $input->$method(true);
    self::assertTrue($input->getAttribute($attribute));

    $input = $this->createControl('awesome');
    $input->$method(false);
    self::assertFalse($input->getAttribute($attribute));

    $input = $this->createControl('awesome');
    $input->$method(null);
    self::assertNull($input->getAttribute($attribute));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with empty name.
   */
  public function testEmptyName1(): void
  {
    $this->expectException(\LogicException::class);

    $this->createControl('');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test constructor with empty name.
   */
  public function testEmptyName2(): void
  {
    $this->expectException(\LogicException::class);

    $this->createControl(null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a integer valued attribute.
   *
   * @param string $method
   * @param string $attribute
   *
   * @dataProvider integerAttributes
   */
  public function testInteger(string $method, string $attribute): void
  {
    $input = $this->createControl('awesome');
    $input->$method(0);
    self::assertSame(0, $input->getAttribute($attribute));

    $input = $this->createControl('awesome');
    $input->$method(123);
    self::assertSame(123, $input->getAttribute($attribute));

    $input = $this->createControl('awesome');
    $input->$method(null);
    self::assertNull($input->getAttribute($attribute));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a string valued attribute.
   *
   * @param string $method
   * @param string $attribute
   *
   * @dataProvider stringAttributes
   */
  public function testString(string $method, string $attribute): void
  {
    $input = $this->createControl('awesome');
    $input->$method('satori');
    self::assertSame('satori', $input->getAttribute($attribute));

    $input = $this->createControl('awesome');
    $input->$method(null);
    self::assertNull($input->getAttribute($attribute));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
