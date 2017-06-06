<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class CheckboxControlTest Test class for testing SatBased\Html\Form\CheckboxControl class.
 */
class CheckboxControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test prefix and postfix labels.
   */
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('name');
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
   * Test submit value.
   * In form unchecked.
   * In POST unchecked.
   */
  public function testSubmittedValue1()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test1');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value has not set.
    self::assertFalse($values['test1']);
    // Value has not change.
    self::assertArrayNotHasKey('test1', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST checked
   */
  public function testSubmittedValue2()
  {
    $_POST['test2'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test2');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST.
    self::assertTrue($values['test2']);

    // Assert value has changed.
    self::assertNotEmpty($changed['test2']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form checked.
   * In POST unchecked.
   */
  public function testSubmittedValue3()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test3');
    $input->setValue(true);
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST checkbox unchecked.
    self::assertFalse($values['test3']);

    // Value is change.
    self::assertNotEmpty($changed['test3']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST checked
   */
  public function testSubmittedValue4()
  {
    $_POST['test4'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test4');
    $input->setValue(true);
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value set from POST.
    self::assertTrue($values['test4']);

    // Value has not changed.
    self::assertArrayNotHasKey('test4', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value with alternative values.
   *
   * In form unchecked.
   * In POST unchecked.
   */
  public function testSubmittedValue5()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test5');
    $input->useValues(1, 0);
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value has not set.
    self::assertSame(0, $values['test5']);
    // Value has not change.
    self::assertArrayNotHasKey('test5', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value with alternative values.
   *
   * In form unchecked.
   * In POST checked.
   */
  public function testSubmittedValue6()
  {
    $_POST['test6'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test6');
    $input->useValues('1', '0');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Value has not set.
    self::assertSame('1', $values['test6']);

    // Value has changed.
    self::assertArrayHasKey('test6', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
