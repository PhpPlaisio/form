<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Cleaner\PruneWhitespaceCleaner;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextAreaControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class TextAreaControl.
 */
class TextAreaControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new TextAreaControl('name');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);

    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<textarea');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '/textarea>World');
    self::assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   */
  public function testPruneWhitespaceNoChanged()
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new TextAreaControl('test');
    $input->setValue('Hello World!');
    $fieldset->addFormControl($input);

    // Set cleaner for textarea field (default it off).
    $input->setCleaner(PruneWhitespaceCleaner::get());

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After clean '  Hello    World!   ' must be equal 'Hello World!'.
    self::assertEquals('Hello World!', $values['test']);

    // Value not change.
    self::assertArrayNotHasKey('test', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submit value.
   */
  public function testSubmittedValue()
  {
    $_POST['test'] = 'Hello World!';

    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new TextAreaControl('test');
    $input->setValue('Hi World!');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEquals('Hello World!', $values['test']);

    // Value is change.
    self::assertNotEmpty($changed['test']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
