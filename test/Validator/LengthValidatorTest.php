<?php

namespace SetBased\Abc\Form\Test\Validator;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;
use SetBased\Abc\Form\Validator\LengthValidator;

/**
 * Test cases for class LengthValidator.
 */
class LengthValidatorTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests a too long string.
   */
  public function testInvalidString1()
  {
    $_POST['value'] = 'Hyperbolicsyllabicsesquedalymistic';
    $form           = $this->setupForm1(10, 20);

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests a too short string.
   */
  public function testInvalidString2()
  {
    $_POST['value'] = 'Isaac';
    $form           = $this->setupForm1(10, 20);

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests valid string with minimum length.
   */
  public function testValidString1()
  {
    $_POST['value'] = 'hot';
    $form           = $this->setupForm1(3, 8);

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests valid string with maximum length.
   */
  public function testValidString2()
  {
    $_POST['value'] = 'buttered';
    $form           = $this->setupForm1(3, 8);

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests a valid string.
   */
  public function testValidString3()
  {
    $_POST['value'] = 'soul';
    $form           = $this->setupForm1(3, 8);

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control which must be a valid length.
   *
   * @param int $min The minimum valid length.
   * @param int $max The maximum valid length.
   *
   * @return TestForm
   */
  private function setupForm1($min, $max)
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new TextControl('value');
    $input->addValidator(new LengthValidator($min, $max));
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

