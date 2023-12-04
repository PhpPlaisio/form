<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\ImmutableTestCase;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\PlaisioTestCase;

/**
 * Unit tests for class CheckboxControl.
 */
class CheckboxControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use ImmutableTestCase;
  use InputElementTest1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values of immutable form control do not change.
   */
  public function testImmutable(): void
  {
    $_POST['immutable2'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('immutable1');
    $input->setImmutable(true)
          ->setValue(true);
    $fieldset->addFormControl($input);

    $input = new CheckboxControl('immutable2');
    $input->setImmutable(true)
          ->setValue(false);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method = $form->execute();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);
    self::assertTrue($values['immutable1']);
    self::assertArrayNotHasKey('immutable1', $changed);
    self::assertFalse($values['immutable2']);
    self::assertArrayNotHasKey('immutable2', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new CheckboxControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values of mutable form control do change.
   */
  public function testMutable(): void
  {
    $_POST['immutable2'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('immutable1');
    $input->setMutable(true)
          ->setValue(true);
    $fieldset->addFormControl($input);

    $input = new CheckboxControl('immutable2');
    $input->setMutable(true)
          ->setValue(false);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);

    self::assertFalse($values['immutable1']);
    self::assertArrayHasKey('immutable1', $changed);

    self::assertTrue($values['immutable2']);
    self::assertArrayHasKey('immutable2', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST unchecked.
   */
  public function testSubmittedValue1(): void
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test1');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);
    self::assertFalse($values['test1']);
    self::assertArrayNotHasKey('test1', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST checked
   */
  public function testSubmittedValue2(): void
  {
    $_POST['test2'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test2');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);
    self::assertTrue($values['test2']);
    self::assertNotEmpty($changed['test2']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form checked.
   * In POST unchecked.
   */
  public function testSubmittedValue3(): void
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test3');
    $input->setValue(true);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);
    self::assertFalse($values['test3']);
    self::assertNotEmpty($changed['test3']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   * In form unchecked.
   * In POST checked
   */
  public function testSubmittedValue4(): void
  {
    $_POST['test4'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test4');
    $input->setValue(true);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);
    self::assertTrue($values['test4']);
    self::assertArrayNotHasKey('test4', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value with alternative values.
   *
   * In form unchecked.
   * In POST unchecked.
   */
  public function testSubmittedValue5(): void
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test5');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);
    self::assertSame(false, $values['test5']);
    self::assertArrayNotHasKey('test5', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value with alternative values.
   *
   * In form unchecked.
   * In POST checked.
   */
  public function testSubmittedValue6(): void
  {
    $_POST['test6'] = 'on';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxControl('test6');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);
    self::assertTrue($values['test6']);
    self::assertArrayHasKey('test6', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of the form control to be tested.
   *
   * @param string $name the name of the form control.
   *
   * @return SimpleControl
   */
  protected function createControl(string $name): SimpleControl
  {
    return new CheckboxControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'checkbox';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
