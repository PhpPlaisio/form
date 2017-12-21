<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Validator;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Form\Control\CompoundControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Validator\ProxyCompoundValidator;

/**
 * Test cases for class ProxyCompoundValidator.
 */
class ProxyCompoundValidatorTest extends TestCase
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
   * @param CompoundControl $control The form control.
   * @param mixed           $data    The additional data.
   *
   * @return bool
   */
  public function validate($control, $data = null)
  {
    $input = $control->getFormControlByName('input');

    return ($input->getSubmittedValue()==$data);
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
    $fieldset->addFormControl($input);
    $form->addValidator(new ProxyCompoundValidator([$this, 'validate'], $data));

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

