<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\PushControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Abstract parent class for unit tests for child classes of PushControl.
 */
abstract class PushControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = $this->getControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test is submit trigger.
   */
  public function testIsSubmitTrigger()
  {
    $input = $this->getControl('trigger');

    self::assertTrue($input->isSubmitTrigger());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method loadSubmittedValuesBase must set the white list value but not the change controls.
   */
  public function testLoadSubmittedValues1()
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
    $input->setValue('Do not push');
    $fieldset->addFormControl($input);

    $_POST['button'] = 'Do not push';

    $form->loadSubmittedValues();

    $values = $form->getValues();
    self::assertEquals('Do not push', $values['button']);

    $changed = $form->getChangedControls();
    self::assertArrayNotHasKey('button', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method loadSubmittedValuesBase must only load white listed values.
   */
  public function testLoadSubmittedValues2()
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
    $input->setValue('Do not push');
    $fieldset->addFormControl($input);

    $_POST['button'] = 'Do push me';

    $form->loadSubmittedValues();

    $values = $form->getValues();
    self::assertArrayNotHasKey('button', $values);

    $changed = $form->getChangedControls();
    self::assertArrayNotHasKey('button', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method mergeValue() has no effect for buttons.
   */
  public function testMergeValues()
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
    $input->setValue("Do not push");
    $fieldset->addFormControl($input);

    // Set the values for button.
    $values['button'] = 'Do push me';
    $form->mergeValues($values);

    // Generate HTML.
    $html = $form->getHtml();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='".$this->getControlType()."']");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test prefix and postfix.
   */
  public function testPrefixAndPostfix()
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('name');
    $fieldset->addFormControl($input);

    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $html = $form->getHtml();

    $pos = strpos($html, 'Hello<input');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    self::assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method setValue() has no effect for buttons.
   */
  public function testSetValues()
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
    $input->setValue("Do not push");
    $fieldset->addFormControl($input);

    // Set the values for button.
    $values['button'] = 'Do push me';
    $form->setValues($values);

    // Generate HTML.
    $html = $form->getHtml();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='".$this->getControlType()."']");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a concrete instance of PushControl.
   *
   * @param string $name The of the control.
   *
   * @return PushControl
   */
  abstract protected function getControl($name);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return type of form control.
   *
   * @return string
   */
  abstract protected function getControlType();

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
