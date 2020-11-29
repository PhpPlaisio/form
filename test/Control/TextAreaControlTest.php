<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Cleaner\PruneWhitespaceCleaner;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\TextAreaControl;
use Plaisio\Form\Test\Control\Traits\Immutable;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Test\TestForm;

/**
 * Unit tests for class TextAreaControl.
 */
class TextAreaControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use Immutable;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for methods setPrefix() and setPostfix().
   */
  public function testPrefixAndPostfix(): void
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextAreaControl('name');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    $pos = strpos($html, 'Hello<textarea');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '/textarea>World');
    self::assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   */
  public function testPruneWhitespaceNoChanged(): void
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new TestForm();
    $fieldset = new FieldSet();
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
  public function testSubmittedValue(): void
  {
    $_POST['test'] = 'Hello World!';

    $form     = new TestForm();
    $fieldset = new FieldSet();
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
  /**
   * Test labels are casted to strings.
   */
  public function testWithNumericValues(): void
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextAreaControl('day_id');
    $input->setValue(pi());
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    self::assertNotEmpty($html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a TextAreaControl form control.
   *
   * @param string $name The name of the form control.
   *
   * @return TextAreaControl
   */
  protected function getControl(string $name): TextAreaControl
  {
    return new TextAreaControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
