<?php

namespace SetBased\Abc\Form\Test\Validator;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Validator\ProxyValidator;

/**
 * Test cases for class ProxyValidator.
 */
class ProxyValidatorTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid data.
   */
  public function test01()
  {
    $_POST['input'] = 'valid';
    $form           = $this->setupForm1('valid');

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with invalid data.
   */
  public function test02()
  {
    $_POST['input'] = 'invalid';
    $form           = $this->setupForm1('valid');

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid data and no additional data.
   */
  public function test03()
  {
    $_POST['input'] = '';
    $form           = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with invalid data and no additional data.
   */
  public function test04()
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
  public function validate($control, $data = null)
  {
    return ($control->getSubmittedValue()==$data);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid email address) values.
   *
   * @param mixed $data The additional data.
   *
   * @return RawForm
   */
  private function setupForm1($data = null)
  {
    $form     = new RawForm();
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

