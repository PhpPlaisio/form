<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Cleaner\DateCleaner;
use Plaisio\Form\Cleaner\PruneWhitespaceCleaner;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\Formatter\DateFormatter;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\ImmutableTestCase;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Control\Traits\InputElementTest2;

/**
 * Unit tests for class TextControl.
 */
class TextControlTest extends SimpleControlTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use ImmutableTestCase;
  use InputElementTest1;
  use InputElementTest2;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning and formatting is done before testing value of the form control has changed.
   * For text field whitespaceOnly cleaner set default.
   */
  public function testDateFormattingAndCleaning(): void
  {
    $_POST['birthday'] = '10.04.1966';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('birthday');
    $input->setValue('1966-04-10')
          ->addCleaner(new DateCleaner('d-m-Y', '-', '/-. '))
          ->setFormatter(new DateFormatter('d-m-Y'));
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);

    // After formatting and clean the date must be in ISO 8601 format.
    self::assertEquals('1966-04-10', $values['birthday']);

    // Effectively the date is not changed.
    self::assertArrayNotHasKey('birthday', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning is done before testing value of the form control has changed.
   * For text field whitespaceOnly cleaner set default.
   */
  public function testPruneWhitespaceNoChanged(): void
  {
    $_POST['test'] = '  Hello    World!   ';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('test');
    $input->setValue('Hello World!')
          ->addCleaner(PruneWhitespaceCleaner::get());
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);

    // After clean '  Hello    World!   ' must be equal 'Hello World!'.
    self::assertEquals('Hello World!', $values['test']);

    // Effectively the value is not changed.
    self::assertArrayNotHasKey('test', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values are always converted to strings.
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

    $input = new TextControl('string1');
    $input->setValue(1);
    $fieldset->addFormControl($input);

    $input = new TextControl('string2');
    $input->setValue(0);
    $fieldset->addFormControl($input);

    $input = new TextControl('bool1');
    $input->setValue(false);
    $fieldset->addFormControl($input);

    $input = new TextControl('bool2');
    $input->setValue(true);
    $fieldset->addFormControl($input);

    $input = new TextControl('bool3');
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertSame('1', $values['string1']);
    self::assertSame('1', $values['string2']);
    self::assertSame('1', $values['bool1']);
    self::assertSame('1', $values['bool1']);
    self::assertSame('1', $values['bool2']);
    self::assertSame('1', $values['bool3']);
    self::assertSame(['string2' => true, 'bool1' => true, 'bool3' => true], $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(string $name): SimpleControl
  {
    $input = new TextControl($name);
    $input->addCleaner(PruneWhitespaceCleaner::get());

    return $input;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return type for form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'text';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
