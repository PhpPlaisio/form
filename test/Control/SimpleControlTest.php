<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\SimpleControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 *  Abstract parent class for unit tests for child classes of SimpleControl.
 */
abstract class SimpleControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a submitted value '0'.
   */
  public function test1Empty1()
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
  public function test1Empty2()
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
  public function testIsSubmitTrigger()
  {
    $input = $this->getControl('trigger');

    self::assertFalse($input->isSubmitTrigger());
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = $this->getControl('name');
    $input->setValue('1');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
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
  public function testValid101()
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
  public function testValid102()
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
  public function testValid103()
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
   * @param $name
   *
   * @return SimpleControl
   */
  abstract protected function getControl($name);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control.
   *
   * @param string $value The value of the form control
   *
   * @return TestForm
   */
  private function setupForm1($value)
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = $this->getControl('name');
    if (isset($value)) $input->setValue($value);
    $fieldset->addFormControl($input);

    $input = new SubmitControl('submit');
    $input->setValue('submit');
    $input->setMethod('handler');
    $fieldset->addFormControl($input);

    $form->execute();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

