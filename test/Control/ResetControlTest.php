<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ResetControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\TestInputElement;
use Plaisio\Form\Test\PlaisioTestCase;

/**
 * Unit tests for class ResetControl.
 */
class ResetControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use TestInputElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method loadSubmittedValuesBase must set the white list value but not the change controls.
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
    self::assertSame('handleEchoForm', $method);
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
    $html = $form->getHtml();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='reset']");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for methods setPrefix() and setPostfix().
   */
  public function testPrefixAndPostfix(): void
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new ResetControl('name');
    $input->setValue('1')
          ->setPrefix('Hello')
          ->setPostfix('World');
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    $pos = strpos($html, 'Hello<input');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    self::assertNotEquals(false, $pos);
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
    $html = $form->getHtml();

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
