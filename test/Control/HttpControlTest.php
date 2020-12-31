<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\HttpControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Control\UrlControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\ImmutableTest;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Control\Traits\InputElementTest2;
use Plaisio\Form\Test\PlaisioTestCase;

/**
 * Unit tests for class HttpControl.
 */
class HttpControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use ImmutableTest;
  use InputElementTest1;
  use InputElementTest2;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns hidden.
   *
   * @return string
   */
  public function getControlClass(): string
  {
    return 'http';
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testNonHttp(): void
  {
    $_POST['test'] = 'ftp://ftp.setbased.nl';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new HttpControl('test');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $invalid = $form->getInvalidControls();

    self::assertSame('handleEchoForm', $method);
    self::assertCount(1, $invalid);
  }

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
    return new HttpControl($name);
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
   * @return mixed
   */
  protected function getValidInitialValue()
  {
    return 'http://www.google.com/';
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
