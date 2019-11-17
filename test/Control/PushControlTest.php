<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\PushControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Test\TestForm;

/**
 * Abstract parent class for unit tests for child classes of PushControl.
 */
abstract class PushControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = $this->getControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test is submit trigger.
   */
  public function testIsSubmitTrigger(): void
  {
    $input = $this->getControl('trigger');

    self::assertTrue($input->isSubmitTrigger());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method loadSubmittedValuesBase must set the white list value but not the change controls.
   */
  public function testLoadSubmittedValues1(): void
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
    $input->setValue('Do not push');
    $fieldset->addFormControl($input);

    $_POST['button'] = 'Do not push';

    $form->loadSubmittedValues();

    $values = $form->getValues();
    self::assertEquals('Do not push', $values['button']);

    $changed = $form->getChangedControls();
    self::assertArrayNotHasKey('button', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method loadSubmittedValuesBase must only load white listed values.
   */
  public function testLoadSubmittedValues2(): void
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
    $input->setValue('Do not push');
    $fieldset->addFormControl($input);

    $_POST['button'] = 'Do push me';

    $form->loadSubmittedValues();

    $values = $form->getValues();
    if (get_class($input)!=ForceSubmitControl::class)
    {
      self::assertArrayNotHasKey('button', $values);
    }
    else
    {
      self::assertArrayHasKey('button', $values);
    }

    $changed = $form->getChangedControls();
    self::assertArrayNotHasKey('button', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method loadSubmittedValuesBase must work int values too.
   */
  public function testLoadSubmittedValues3(): void
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
    $input->setValue(123);
    $fieldset->addFormControl($input);

    $_POST['button'] = '123';

    $form->loadSubmittedValues();

    $values = $form->getValues();
    self::assertEquals(123, $values['button']);

    $changed = $form->getChangedControls();
    self::assertArrayNotHasKey('button', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method mergeValue() has no effect for buttons.
   */
  public function testMergeValues(): void
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
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
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='".$this->getControlType()."']");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for methods setPrefix() and setPostfix().
   */
  public function testPrefixAndPostfix(): void
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('name');
    $fieldset->addFormControl($input);

    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $html = $form->getHtml();

    $pos = strpos($html, 'Hello<input');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    self::assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method searchSubmitHandler must return the appropriate handler.
   */
  public function testSearchSubmitHandler1(): void
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
    $input->setValue('Do not push');
    $input->setMethod('myMethod');
    $fieldset->addFormControl($input);

    $_POST['button'] = 'Do not push';

    $handler = $form->execute();

    self::assertEquals('myMethod', $handler);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method searchSubmitHandler must return the appropriate handler with int values too.
   */
  public function testSearchSubmitHandler2(): void
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('123');
    $input->setValue(456);
    $input->setMethod('myMethod');
    $fieldset->addFormControl($input);

    $_POST['123'] = '456';

    $handler = $form->execute();

    self::assertEquals('myMethod', $handler);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method setValue() has no effect for buttons.
   */
  public function testSetValues(): void
  {
    // Create form.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('button');
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
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='".$this->getControlType()."']");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a concrete instance of PushControl.
   *
   * @param string $name The of the control.
   *
   * @return PushControl
   */
  abstract protected function getControl(string $name): SimpleControl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return type of form control.
   *
   * @return string
   */
  abstract protected function getControlType(): string;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
