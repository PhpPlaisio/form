<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ResetControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Unit tests for class ResetControl.
 */
class ResetControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElementTest1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method loadSubmittedValuesBase must set the whitelist value but not the changed form controls.
   */
  public function testLoadSubmittedValues1(): void
  {
    $_POST['button'] = 'Do not push';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new ResetControl('button');
    $input->setValue('Do not push');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertNull($form->isValid());
    self::assertSame('', $method);
    self::assertArrayNotHasKey('button', $values);
    self::assertArrayNotHasKey('button', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method mergeValue() has no effect for buttons.
   */
  public function testMergeValues(): void
  {
    // Create form.
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new ResetControl('button');
    $input->setValue("Do not push");
    $fieldset->addFormControl($input);

    // Set the values for button.
    $values['button'] = 'Do push me';
    $form->mergeValues($values);

    // Generate HTML.
    $html = $form->htmlForm();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='reset']");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method setValue() has no effect for buttons.
   */
  public function testSetValues(): void
  {
    // Create form.
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new ResetControl('button');
    $input->setValue("Do not push");
    $fieldset->addFormControl($input);

    // Set the values for button.
    $values['button'] = 'Do push me';
    $form->setValues($values);

    // Generate HTML.
    $html = $form->htmlForm();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='reset']");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(string $name): SimpleControl
  {
    return new ResetControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'reset';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
