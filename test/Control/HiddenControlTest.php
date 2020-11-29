<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Cleaner\PruneWhitespaceCleaner;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\HiddenControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Test\Control\Traits\Immutable;
use Plaisio\Form\Test\TestForm;

/**
 * Unit tests for class HiddenControl.
 */
class HiddenControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  use Immutable;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new HiddenControl('hidden');

    self::assertSame(true, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test change value.
   */
  public function testValue(): void
  {
    $_POST['test'] = 'New value';

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new HiddenControl('test');
    $input->setValue('Old value');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    $changed = $form->getChangedControls();

    // Value is change.
    self::assertNotEmpty($changed['test']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   */
  public function testWhitespace(): void
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new HiddenControl('test');
    $input->setValue('Hello World!');
    $fieldset->addFormControl($input);

    // Set cleaner for hidden field (default it off).
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
   * @inheritdoc
   */
  protected function getControl(string $name): SimpleControl
  {
    return new HiddenControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
