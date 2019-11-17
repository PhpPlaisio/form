<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\HiddenControl;
use Plaisio\Form\Control\PasswordControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Control\TextAreaControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Test\TestForm;
use Plaisio\Form\Validator\MandatoryValidator;

/**
 * Test cases for class MandatoryValidator.
 */
class MandatoryValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory text, password, hidden or textarea form control with value @c null, @c false, or @c '', is invalid.
   */
  public function testInvalidEmpty(): void
  {
    $types  = ['text', 'password', 'hidden', 'textarea', 'checkbox'];
    $values = [null, false, ''];

    foreach ($types as $type)
    {
      foreach ($values as $value)
      {

        $_POST['input'] = $value;
        $control        = null;
        switch ($type)
        {
          case 'text':
            $control = new TextControl('input');
            break;

          case 'password':
            $control = new PasswordControl('input');
            break;

          case 'hidden':
            $control = new HiddenControl('input');
            break;

          case 'textarea':
            $control = new TextAreaControl('input');
            break;

          case 'checkbox':
            $control = new CheckboxControl('input');
            break;
        }
        $form = $this->setupForm1($control);

        self::assertFalse($form->validate(),
                          sprintf("type: '%s', value: '%s'.", $type, var_export($value, true)));
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /** A mandatory unchecked checked box is invalid.
   */
  public function testInvalidUncheckedCheckbox(): void
  {
    $_POST = [];
    $form  = $this->setupForm2();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory text, password, or textarea form control with whitespace is invalid.
   */
  public function testInvalidWhitespace(): void
  {
    $types  = ['text', 'password', 'textarea'];
    $values = [' ', '  ', " \n  "];

    foreach ($types as $type)
    {
      foreach ($values as $value)
      {

        $_POST['input'] = $value;
        $control        = null;
        switch ($type)
        {
          case 'text':
            $control = new TextControl('input');
            break;

          case 'password':
            $control = new PasswordControl('input');
            break;

          case 'textarea':
            $control = new TextAreaControl('input');
            break;
        }
        $form = $this->setupForm1($control);

        self::assertFalse($form->validate(),
                          sprintf("type: '%s', value: '%s'.", $type, var_export($value, true)));
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory checked checked box is valid.
   */
  public function testValidCheckedCheckbox(): void
  {
    $_POST['box'] = 'on';
    $form         = $this->setupForm2();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  // @todo test with select
  // @todo test with radio
  // @todo test with radios
  // @todo test with checkboxes

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A mandatory non-empty text, password, hidden, or textarea form control is valid.
   */
  public function testValidNoneEmptyText(): void
  {
    $types = ['text', 'password', 'hidden', 'textarea'];

    foreach ($types as $type)
    {
      $_POST['input'] = 'Set Based IT Consultancy';
      $control        = null;
      switch ($type)
      {
        case 'text':
          $control = new TextControl('input');
          break;

        case 'password':
          $control = new PasswordControl('input');
          break;

        case 'hidden':
          $control = new HiddenControl('input');
          break;

        case 'textarea':
          $control = new TextAreaControl('input');
          break;
      }
      $form = $this->setupForm1($control);

      self::assertTrue($form->validate(), sprintf("type: '%s'.", $type));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a single form control of certain type.
   *
   * @param SimpleControl $control
   *
   * @return TestForm
   */
  private function setupForm1($control): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $control;
    $input->addValidator(new MandatoryValidator());
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a checkbox form control.
   *
   * @return TestForm
   */
  private function setupForm2(): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('box');
    $input->addValidator(new MandatoryValidator());
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  // @todo test with select
  // @todo test with radio
  // @todo test with radios
  // @todo test with checkboxes
  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

