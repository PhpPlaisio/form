<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\ConstantControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Test\AbcTestCase;

class ConstantControlTest extends AbcTestCase
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

    // Assert "name" has not be recoded as a changed value.
    self::assertArrayNotHasKey('name', $changed);
  }

  //-------------------------------------------------------------------------------------------------------------------
  private function setupForm1()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
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

