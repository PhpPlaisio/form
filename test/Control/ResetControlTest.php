<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\ResetControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class ResetControl.
 */
class ResetControlTest extends AbcTestCase
{
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

    $input = new ResetControl('button');
    $input->setValue('Do not push');
    $fieldset->addFormControl($input);

    $_POST['button'] = 'Do not push';

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

    $input = new ResetControl('button');
    $input->setValue("Do not push");
    $fieldset->addFormControl($input);

    // Set the values for button.
    $values['button'] = 'Do push me';
    $form->mergeValues($values);

    // Generate HTML.
    $form->prepare();
    $html = $form->generate();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='reset']");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test pre- and postfix.
   */
  public function testPrefixAndPostfix()
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new ResetControl('name');
    $input->setValue('1');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);

    $form->prepare();
    $html = $form->generate();

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

    $input = new ResetControl('button');
    $input->setValue("Do not push");
    $fieldset->addFormControl($input);

    // Set the values for button.
    $values['button'] = 'Do push me';
    $form->setValues($values);

    // Generate HTML.
    $form->prepare();
    $html = $form->generate();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='reset']");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
