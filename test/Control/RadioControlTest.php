<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\RadioControl;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Test\TestForm;

/**
 * Unit tests for class PasswordControl.
 */
class RadioControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clicking on a mutable radio button is possible.
   */
  public function testImmutable1(): void
  {
    $_POST['name'] = '2';

    $form = $this->setForm2(1);
    $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertArrayHasKey('name', $values);
    self::assertSame(2, $values['name']);

    self::assertArrayHasKey('name', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values of immutable form control do not change.
   */
  public function testImmutable2(): void
  {
    $_POST['name'] = '2';

    $form = $this->setForm2(2);
    $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertArrayHasKey('name', $values);
    self::assertSame(null, $values['name']);

    self::assertArrayHasKey('name', $changed);
  }

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
    $input->setPrefix('Hello')
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
   * A white list values must be valid.
   */
  public function testValid1(): void
  {
    $_POST['name'] = '2';

    $form = $this->setForm1();
    $form->execute();
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

    $form = $this->setForm2();
    $form->execute();
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

    $form = $this->setForm2();
    $form->execute();
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

    $form = $this->setForm3();
    $form->execute();
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

    $form = $this->setForm1();
    $form->execute();
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

    $form = $this->setForm2();
    $form->execute();
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

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('execute');
    $fieldset->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function setForm2(int $immutable = null): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new RadioControl('name');
    $input->setAttrValue(1)
          ->setValue(1);
    if ($immutable===1) $input->setImmutable(true);
    $fieldset->addFormControl($input);

    $input = new RadioControl('name');
    $input->setAttrValue(2);
    if ($immutable===2) $input->setImmutable(true);
    $fieldset->addFormControl($input);

    $input = new RadioControl('name');
    $input->setAttrValue(3);
    if ($immutable===3) $input->setImmutable(true);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('execute');
    $fieldset->addFormControl($input);

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

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('execute');
    $fieldset->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
