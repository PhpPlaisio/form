<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Control\Control;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Validator\ProxyValidator;

/**
 * Test cases for class ProxyValidator.
 */
class ProxyValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid data.
   */
  public function test01(): void
  {
    $_POST['input'] = 'valid';
    $form           = $this->setupForm1('valid');

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with invalid data.
   */
  public function test02(): void
  {
    $_POST['input'] = 'invalid';
    $form           = $this->setupForm1('valid');

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid data and no additional data.
   */
  public function test03(): void
  {
    $_POST['input'] = '';
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with invalid data and no additional data.
   */
  public function test04(): void
  {
    $_POST['input'] = 'invalid';
    $form           = $this->setupForm1();

    self::assertFalse($form->isValid());
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
  public function validate(Control $control, mixed $data = null): bool
  {
    return ($control->getSubmittedValue()==$data);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid email address) values.
   *
   * @param mixed|null $data The additional data.
   *
   * @return RawForm
   */
  private function setupForm1(mixed $data = null): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('input');
    $input->addValidator(new ProxyValidator([$this, 'validate'], $data));
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $form->execute();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

