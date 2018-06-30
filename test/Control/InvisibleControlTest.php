<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\InvisibleControl;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Test\AbcTestCase;

class InvisibleControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testForm1()
  {
    $_POST['name'] = '2';

    $form    = $this->setupForm1();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // Assert the value of "name" is still "1".
    self::assertEquals('1', $values['name']);

    // Assert "name" has not be recorded as a changed value.
    self::assertArrayNotHasKey('name', $changed);
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new InvisibleControl('hidden');

    self::assertSame(true, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new InvisibleControl('name');
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
  private function setupForm1()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new InvisibleControl('name');
    $input->setValue('1');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------


