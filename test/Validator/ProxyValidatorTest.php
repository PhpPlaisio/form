<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Validator;

use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;
use SetBased\Abc\Form\Validator\ProxyValidator;

/**
 * Test cases for class ProxyValidator.
 */
class ProxyValidatorTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid data.
   */
  public function test01(): void
  {
    $_POST['input'] = 'valid';
    $form           = $this->setupForm1('valid');

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with invalid data.
   */
  public function test02(): void
  {
    $_POST['input'] = 'invalid';
    $form           = $this->setupForm1('valid');

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid data and no additional data.
   */
  public function test03(): void
  {
    $_POST['input'] = '';
    $form           = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with invalid data and no additional data.
   */
  public function test04(): void
  {
    $_POST['input'] = 'invalid';
    $form           = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the value of the form control meets the conditions of this validator. Returns false otherwise.
   *
   * @param Control $control The form control.
   * @param mixed   $data    The additional data.
   *
   * @return bool
   */
  public function validate(Control $control, $data = null): bool
  {
    return ($control->getSubmittedValue()==$data);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid email address) values.
   *
   * @param mixed $data The additional data.
   *
   * @return TestForm
   */
  private function setupForm1($data = null): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new TextControl('input');
    $input->addValidator(new ProxyValidator([$this, 'validate'], $data));
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

