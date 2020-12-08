<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Control\SubmitControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\PlaisioTestCase;

/**
 *  Abstract parent class for unit tests for child classes of SimpleControl.
 */
abstract class SimpleControlTest extends PlaisioTestCase
{
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
    $input = $this->getControl('trigger');

    self::assertFalse($input->isSubmitTrigger());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for methods setPrefix() and setPostfix().
   */
  public function testPrefixAndPostfix(): void
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('name');
    $input->setValue('1')
          ->setPrefix('Hello')
          ->setPostfix('World');
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    $pos = strpos($html, 'Hello<input');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    self::assertNotEquals(false, $pos);
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
   * Returns the concrete SimpleControl
   *
   * @param string $name The name of the form control.
   *
   * @return SimpleControl
   */
  abstract protected function getControl(string $name): SimpleControl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control.
   *
   * @param string|null $value The value of the form control
   *
   * @return RawForm
   */
  private function setupForm1(?string $value): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('name');
    if (isset($value)) $input->setValue($value);
    $fieldset->addFormControl($input);

    $input = new SubmitControl('submit');
    $input->setValue('submit')
          ->setMethod('handler');
    $fieldset->addFormControl($input);

    $form->execute();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

