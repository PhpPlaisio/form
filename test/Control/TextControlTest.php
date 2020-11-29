<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Cleaner\DateCleaner;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\Formatter\DateFormatter;
use Plaisio\Form\Test\Control\Traits\Immutable;
use Plaisio\Form\Test\TestForm;

/**
 * Unit tests for class TextControl.
 */
class TextControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  use Immutable;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning and formatting is done before testing value of the form control has changed.
   * For text field whitespace cleaner set default.
   */
  public function testDateFormattingAndCleaning(): void
  {
    $_POST['birthday'] = '10.04.1966';

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('birthday');
    $input->setValue('1966-04-10');
    $input->setCleaner(new DateCleaner('d-m-Y', '-', '/-. '));
    $input->setFormatter(new DateFormatter('d-m-Y'));
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After formatting and clean the date must be in ISO 8601 format.
    self::assertEquals('1966-04-10', $values['birthday']);

    // Effectively the date is not changed.
    self::assertArrayNotHasKey('birthday', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   * For text field whitespace cleaner set default.
   */
  public function testPruneWhitespaceNoChanged(): void
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('test');
    $input->setValue('Hello World!');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    // After clean '  Hello    World!   ' must be equal 'Hello World!'.
    self::assertEquals('Hello World!', $values['test']);

    // Effectively the value is not changed.
    self::assertArrayNotHasKey('test', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl(string $name): SimpleControl
  {
    return new TextControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
