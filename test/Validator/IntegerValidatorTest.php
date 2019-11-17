<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Test\TestForm;
use Plaisio\Form\Validator\IntegerValidator;

/**
 * Test cases for class IntegerValidator.
 */
class IntegerValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A string must be invalid.
   */
  public function testInvalidInteger1(): void
  {
    $_POST['integer'] = 'string';
    $form             = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A float must be invalid.
   */
  public function testInvalidInteger2(): void
  {
    $_POST['integer'] = '0.1';
    $form             = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mix of numeric and alpha numeric must be invalid.
   */
  public function testInvalidInteger3(): void
  {
    $_POST['integer'] = '123abc'; // My favorite password ;-)
    $form             = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger4(): void
  {
    $_POST['integer'] = '-9';
    $form             = $this->setupForm2();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger5(): void
  {
    $_POST['integer'] = '-2';
    $form             = $this->setupForm2();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger6(): void
  {
    $_POST['integer'] = '11';
    $form             = $this->setupForm2();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An integer misses the specified range must be invalid.
   */
  public function testInvalidInteger7(): void
  {
    $_POST['integer'] = '23';
    $form             = $this->setupForm2();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A zero must be valid.
   */
  public function testValidInteger1(): void
  {
    $_POST['integer'] = 0;
    $form             = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An integer (posted as string) must be valid.
   */
  public function testValidInteger2(): void
  {
    $_POST['integer'] = '56';
    $form             = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An integer (posted as integer) must be valid.
   */
  public function testValidInteger3(): void
  {
    $_POST['integer'] = 37;
    $form             = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An negative integer (posted as string) must be valid.
   */
  public function testValidInteger4(): void
  {
    $_POST['integer'] = '-11';
    $form             = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An negative integer (posted as integer) must be valid.
   */
  public function testValidInteger5(): void
  {
    $_POST['integer'] = -45;
    $form             = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An minimal integer is within a predetermined range must be valid.
   */
  public function testValidInteger6(): void
  {
    $_POST['integer'] = '-1';
    $form             = $this->setupForm2();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If zero is within a predetermined range must be valid.
   */
  public function testValidInteger7(): void
  {
    $_POST['integer'] = '0';
    $form             = $this->setupForm2();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** An integer is within a predetermined range must be valid.
   */
  public function testValidInteger8(): void
  {
    $_POST['integer'] = '3';
    $form             = $this->setupForm2();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An maximum integer is within a predetermined range must be valid.
   */
  public function testValidInteger9(): void
  {
    $_POST['integer'] = '10';
    $form             = $this->setupForm2();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid inter) values.
   */
  private function setupForm1(): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('integer');
    $input->addValidator(new IntegerValidator());
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid inter) values.
   *
   * @return TestForm
   */
  private function setupForm2(): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('integer');
    $input->addValidator(new IntegerValidator(-1, 10));
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

