<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\RadioControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class PasswordControl.
 */
class RadioControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new RadioControl('hidden');

    self::assertSame(false, $control->isHidden());
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

    $input = new RadioControl('name');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    $pos = strpos($html, 'Hello<input');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    self::assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white list values must be valid.
   */
  public function testValid1(): void
  {
    $_POST['name'] = '2';

    $form   = $this->setForm1();
    $values = $form->getValues();

    self::assertEquals('2', $values['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white list values must be valid.
   */
  public function testValid2(): void
  {
    $_POST['name'] = '2';

    $form   = $this->setForm2();
    $values = $form->getValues();

    self::assertEquals(2, $values['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid (even whens string and integers are mixed).
   */
  public function testValid3(): void
  {
    $_POST['name'] = '3';

    $form   = $this->setForm2();
    $values = $form->getValues();

    self::assertEquals(3, $values['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid (even whens string and integers are mixed).
   */
  public function testValid4(): void
  {
    $_POST['name'] = '0.0';

    $form   = $this->setForm3();
    $values = $form->getValues();

    self::assertEquals('0.0', $values['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white list values must be value.
   */
  public function testWhiteList1(): void
  {
    $_POST['name'] = 'ten';

    $form   = $this->setForm1();
    $values = $form->getValues();

    self::assertArrayHasKey('name', $values);
    self::assertNull($values['name']);
    self::assertEmpty($form->getChangedControls());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white list values must be value.
   */
  public function testWhiteList2(): void
  {
    $_POST['name'] = '10';

    $form    = $this->setForm2();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertArrayHasKey('name', $values);
    self::assertNull($values['name']);

    self::assertNotEmpty($changed['name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form for radio.
   */
  private function setForm1(): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new RadioControl('name');
    $input->setAttrValue('1');
    $fieldset->addFormControl($input);

    $input = new RadioControl('name');
    $input->setAttrValue('2');
    $fieldset->addFormControl($input);

    $input = new RadioControl('name');
    $input->setAttrValue('3');
    $fieldset->addFormControl($input);

    $form->getHtml();
    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function setForm2(): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new RadioControl('name');
    $input->setAttrValue(1);
    $input->setValue(1);
    $fieldset->addFormControl($input);

    $input = new RadioControl('name');
    $input->setAttrValue(2);
    $fieldset->addFormControl($input);

    $input = new RadioControl('name');
    $input->setAttrValue(3);
    $fieldset->addFormControl($input);

    $form->getHtml();
    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function setForm3(): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new RadioControl('name');
    $input->setAttrValue('0');
    $fieldset->addFormControl($input);

    $input = new RadioControl('name');
    $input->setAttrValue('0.0');
    $input->setValue('0.0');
    $fieldset->addFormControl($input);

    $input = new RadioControl('name');
    $input->setAttrValue(' ');
    $fieldset->addFormControl($input);

    $form->getHtml();
    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
