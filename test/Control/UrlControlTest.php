<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Control\UrlControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\ImmutableTestCase;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Control\Traits\InputElementTest2;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Unit tests for class UrlControl.
 */
class UrlControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use ImmutableTestCase;
  use InputElementTest1;
  use InputElementTest2;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   * For text field whitespaceOnly cleaner set default.
   */
  public function testPruneWhitespaceNoChanged(): void
  {
    $_POST['test'] = '  www.setbased.nl ';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new UrlControl('test');
    $input->setValue('http://www.setbased.nl/');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);

    // After clean '  www.setbased.nl ' must be equal 'http://www.setbased.nl/'.
    self::assertEquals('http://www.setbased.nl/', $values['test']);

    // Effectively the value is not changed.
    self::assertArrayNotHasKey('test', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(string $name): SimpleControl
  {
    return new UrlControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'url';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid initial value.
   *
   * @return string
   */
  protected function getValidInitialValue(): string
  {
    return 'https://www.google.com/';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid submitted value (different form initial value).
   *
   * @return string
   */
  protected function getValidSubmittedValue(): string
  {
    return 'http://www.setbased.nl/';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
