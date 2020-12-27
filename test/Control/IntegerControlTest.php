<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\IntegerControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\TestInputElement;

/**
 * Unit tests for class IntegerControl.
 */
class IntegerControlTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use TestInputElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with illegal submitted value.
   */
  public function testInvalidSubmittedValue01(): void
  {
    $_POST['year'] = 'Hello, world';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new IntegerControl('year');
    $input->setAttrMin('2000')
          ->setAttrMax('2020')
          ->setValue(2018);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertFalse($form->isValid());
    self::assertSame('handleEchoForm', $method);
    self::assertSame('Hello, world', $values['year']);
    self::assertArrayHasKey('year', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with illegal submitted value.
   */
  public function testInvalidSubmittedValue02(): void
  {
    $_POST['year'] = '1900';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new IntegerControl('year');
    $input->setAttrMin('2000')
          ->setAttrMax('2020')
          ->setValue(2018);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertFalse($form->isValid());
    self::assertSame('handleEchoForm', $method);
    self::assertSame(1900, $values['year']);
    self::assertArrayHasKey('year', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values are always converted to integers.
   */
  public function testTypeConversion(): void
  {
    $_POST = ['string1' => '1',
              'string2' => '1',
              'bool1'   => '1',
              'bool2'   => '1',
              'bool3'   => '1'];

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new IntegerControl('string1');
    $input->setValue('1');
    $fieldset->addFormControl($input);

    $input = new IntegerControl('string2');
    $fieldset->addFormControl($input);

    $input = new IntegerControl('bool1');
    $input->setValue(false);
    $fieldset->addFormControl($input);

    $input = new IntegerControl('bool2');
    $input->setValue(true);
    $fieldset->addFormControl($input);

    $input = new IntegerControl('bool3');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertSame(1, $values['string1']);
    self::assertSame(1, $values['string2']);
    self::assertSame(1, $values['bool1']);
    self::assertSame(1, $values['bool1']);
    self::assertSame(1, $values['bool2']);
    self::assertSame(1, $values['bool3']);
    self::assertSame(['string2' => true, 'bool1' => true, 'bool3' => true], $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning and formatting is done before testing value of the form control has changed.
   * For text field whitespaceOnly cleaner set default.
   */
  public function testValidSubmittedValue(): void
  {
    $_POST['year'] = '2018';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new IntegerControl('year');
    $input->setAttrMin('2000')
          ->setAttrMax('2020')
          ->setValue('2018');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertSame(2018, $values['year']);
    self::assertArrayNotHasKey('year', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(string $name): SimpleControl
  {
    return new IntegerControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'number';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid initial value.
   *
   * @return mixed
   */
  protected function getValidInitialValue()
  {
    return 123;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid submitted value (different form initial value).
   *
   * @return string
   */
  protected function getValidSubmittedValue(): string
  {
    return '456';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
