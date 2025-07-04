<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\SilentControl;
use Plaisio\Form\Control\SubmitControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Unit tests for class SilentControl.
 */
class SilentControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElementTest1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns hidden.
   *
   * @return string
   */
  public function getControlClass(): string
  {
    return 'silent';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0'.
   */
  public function test1Empty1(): void
  {
    $name            = 0;
    $_POST['name']   = $name;
    $_POST['submit'] = 'submit';

    $form    = $this->setupForm1(null);
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertArrayNotHasKey('name', $values);
    self::assertArrayNotHasKey('name', $changed);
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

    self::assertArrayNotHasKey('name', $values);
    self::assertArrayNotHasKey('name', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new SilentControl('hidden');

    self::assertTrue($control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test is submit trigger.
   */
  public function testIsSubmitTrigger(): void
  {
    $input = new SilentControl('trigger');

    self::assertFalse($input->isSubmitTrigger());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submitted values are displayed in the form controls after to form has been executed.
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

    self::assertArrayNotHasKey('name', $values);
    self::assertArrayNotHasKey('name', $changed);
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

    self::assertArrayNotHasKey('name', $values);
    self::assertArrayNotHasKey('name', $changed);
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

    self::assertArrayNotHasKey($name, $values);
    self::assertArrayNotHasKey($name, $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test change value.
   */
  public function testValue(): void
  {
    $_POST['test'] = 'New value';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new SilentControl('test');
    $input->setValue('Old value');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertArrayNotHasKey('test', $values);
    self::assertArrayNotHasKey('test', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return an instance of SilentControl.
   *
   * @param string $name the name of the form control.
   *
   * @return SilentControl
   */
  protected function createControl(string $name): SilentControl
  {
    return new SilentControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'hidden';
  }

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
   * Returns a valid value for a TexControl.
   *
   * @return string
   */
  protected function getValidValue(): string
  {
    return 'Hello, World!';
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

    $input = new SilentControl('name');
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
