<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\InvisibleControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Unit tests for class InvisibleControl.
 */
class InvisibleControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElementTest1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invisible.
   *
   * @return string
   */
  public function getControlClass(): string
  {
    return 'invisible';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test submitted value is not loaded.
   */
  public function testForm1(): void
  {
    $_POST['name'] = '2';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new InvisibleControl('name');
    $input->setValue('1');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertEquals('1', $values['name']);
    self::assertArrayNotHasKey('name', $changed);
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new InvisibleControl('hidden');

    self::assertSame(true, $control->isHidden());
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
    return new InvisibleControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'hidden';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------


