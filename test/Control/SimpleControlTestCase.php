<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Control\SubmitControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 *  Abstract parent class for unit tests for child classes of CommonSimpleControlTestCase.
 */
abstract class SimpleControlTestCase extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0'.
   */
  public function test1Empty1(): void
  {
    $name            = '0';
    $_POST['name']   = $name;
    $_POST['submit'] = 'submit';

    $form    = $this->setupForm1(null);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEquals($name, $values['name']);
    self::assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0.0'.
   */
  public function test1Empty2(): void
  {
    $name            = '0.0';
    $_POST['name']   = $name;
    $_POST['submit'] = 'submit';

    $form    = $this->setupForm1('');
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEquals($name, $values['name']);
    self::assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test is submit trigger.
   */
  public function testIsSubmitTrigger(): void
  {
    $input = $this->createControl('trigger');

    self::assertFalse($input->isSubmitTrigger());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * TTest submitted values are displayed in the form controls after to form has been executed.
   */
  public function testSubmittedValuesAreEchoed(): void
  {
    $_POST['name']   = $this->getValidSubmittedValue();
    $_POST['submit'] = 'submit';

    $form = $this->setupForm1($this->getValidInitialValue());
    $html = $form->htmlForm();

    self::assertStringContainsString($this->getValidSubmittedValue(), $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value.
   */
  public function testValid101(): void
  {
    $name = 'Set Based IT Consultancy';

    $_POST['name']   = $name;
    $_POST['submit'] = 'submit';

    $form    = $this->setupForm1(null);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEquals($name, $values['name']);
    self::assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted empty value.
   */
  public function testValid102(): void
  {
    $name            = 'Set Based IT Consultancy';
    $_POST['name']   = '';
    $_POST['submit'] = 'submit';

    $form    = $this->setupForm1($name);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEmpty($values['name']);
    self::assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing submitted value.
   */
  public function testValid103(): void
  {
    $name                = 'Set Based IT Consultancy';
    $_POST['other_name'] = '';
    $_POST['submit']     = 'submit';

    $form    = $this->setupForm1($name);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEmpty($values['name']);
    self::assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the concrete CommonSimpleControlTestCase
   *
   * @param string $name The name of the form control.
   *
   * @return SimpleControl
   */
  abstract protected function createControl(string $name): SimpleControl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid initial value.
   *
   * @return mixed
   */
  protected function getValidInitialValue()
  {
    return 'Hello, World!';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid submitted value (different form initial value).
   *
   * @return string
   */
  protected function getValidSubmittedValue(): string
  {
    return 'Bye, bye!';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a simple form control and executes the form.
   *
   * @param mixed $value The value of the form control
   *
   * @return RawForm
   */
  private function setupForm1(mixed $value): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->createControl('name');
    $input->setValue($value);
    $fieldset->addFormControl($input);

    $input = new SubmitControl('submit');
    $input->setValue('submit')
          ->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $form->execute();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

