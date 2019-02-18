<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\ConstantControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class ConstantControl.
 */
class ConstantControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testForm1(): void
  {
    $_POST['name'] = '2';

    $form    = $this->setupForm1();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Assert the value of "name" is still "1".
    self::assertEquals('1', $values['name']);

    // Assert "name" has not be recoded as a changed value.
    self::assertArrayNotHasKey('name', $changed);
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new ConstantControl('hidden');

    self::assertSame(true, $control->isHidden());
  }

  //-------------------------------------------------------------------------------------------------------------------
  private function setupForm1(): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new ConstantControl('name');
    $input->setValue('1');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

