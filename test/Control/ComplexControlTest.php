<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\CheckboxControl;
use Plaisio\Form\Control\ComplexControl;
use Plaisio\Form\Control\Control;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Validator\MandatoryValidator;

/**
 * Unit tests for class ComplexControl.
 */
class ComplexControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var ComplexControl
   */
  private ComplexControl $originComplexControl;

  /**
   * @var SimpleControl
   */
  private SimpleControl $originControl;

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Test find FormControl by name.
   */
  public function testFindFormControlByName(): void
  {
    $form = $this->setForm1();

    // Find form control by name. Must return object.
    $input = $form->findFormControlByName('street');
    self::assertInstanceOf(Control::class, $input);

    // Find form control by name what does not exist. Must return null.
    $input = $form->findFormControlByName('not_exists');
    self::assertEquals(null, $input);

    $input = $form->findFormControlByName('/no_path/not_exists');
    self::assertEquals(null, $input);

    $input = $form->findFormControlByName('/vacation/not_exists');
    self::assertEquals(null, $input);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test find FormControl by path.
   */
  public function testFindFormControlByPath(): void
  {
    $form = $this->setForm1();

    // Find form control by path. Must return object.
    $input = $form->findFormControlByPath('/street');
    self::assertInstanceOf(Control::class, $input);

    $input = $form->findFormControlByPath('/post/street');
    self::assertInstanceOf(Control::class, $input);

    $input = $form->findFormControlByPath('/post/zip-code');
    self::assertInstanceOf(Control::class, $input);

    $input = $form->findFormControlByPath('/vacation/street');
    self::assertInstanceOf(Control::class, $input);

    $input = $form->findFormControlByPath('/vacation/post/street');
    self::assertInstanceOf(Control::class, $input);

    $input = $form->findFormControlByPath('/vacation/post/street');
    self::assertInstanceOf(Control::class, $input);

    // Find form control by path what does not exist. Must return null.
    $input = $form->findFormControlByPath('/not_exists');
    self::assertEquals(null, $input);

    $input = $form->findFormControlByPath('/no_path/not_exists');
    self::assertEquals(null, $input);

    $input = $form->findFormControlByPath('/vacation/not_exists');
    self::assertEquals(null, $input);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get FormControl by name.
   */
  public function testGetFormControlByName(): void
  {
    $form = $this->setForm1();

    // Get form control by name. Must return object.
    $input = $form->getFormControlByName('vacation');
    self::assertInstanceOf(Control::class, $input);

    /** @var ComplexControl $input */
    $input = $input->getFormControlByName('city2');
    self::assertInstanceOf(Control::class, $input);
    self::assertEquals('city2', $input->getName());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get FormControl by path.
   */
  public function testGetFormControlByPath(): void
  {
    $form = $this->setForm1();

    // Get form control by path. Must return object.
    $input = $form->getFormControlByPath('/street');
    self::assertInstanceOf(Control::class, $input);

    $input = $form->getFormControlByPath('/post/street');
    self::assertInstanceOf(Control::class, $input);

    $input = $form->getFormControlByPath('/vacation/street');
    self::assertInstanceOf(Control::class, $input);

    $input = $form->getFormControlByPath('/vacation/post/street');
    self::assertInstanceOf(Control::class, $input);

    $input = $form->getFormControlByPath('/vacation/post/street');
    self::assertInstanceOf(Control::class, $input);
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   */
  public function testGetNotExistsFormControlByName1(): void
  {
    $this->expectException(\LogicException::class);

    $form = $this->setForm1();
    $form->getFormControlByName('not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   */
  public function testGetNotExistsFormControlByName2(): void
  {
    $this->expectException(\LogicException::class);

    $form = $this->setForm1();
    $form->getFormControlByName('/no_path/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   */
  public function testGetNotExistsFormControlByName3(): void
  {
    $this->expectException(\LogicException::class);

    $form = $this->setForm1();
    $form->getFormControlByName('/vacation/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   */
  public function testGetNotExistsFormControlByPath1(): void
  {
    $this->expectException(\LogicException::class);

    $form = $this->setForm1();
    $form->getFormControlByPath('/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   */
  public function testGetNotExistsFormControlByPath2(): void
  {
    $this->expectException(\LogicException::class);

    $form = $this->setForm1();
    $form->getFormControlByPath('street');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   */
  public function testGetNotExistsFormControlByPath3(): void
  {
    $this->expectException(\LogicException::class);

    $form = $this->setForm1();
    $form->getFormControlByPath('/no_path/not_exists');
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   */
  public function testGetNotExistsFormControlByPath4(): void
  {
    $this->expectException(\LogicException::class);

    $form = $this->setForm1();
    $form->getFormControlByPath('/vacation/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new ComplexControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test mergeValues with null.
   */
  public function testMergeValues1(): void
  {
    $form = $this->setForm2();

    /** @var SimpleControl $input4 */
    $input4 = $form->getFormControlByName('field_4');
    $input4->setValue('four');

    $values = $form->getSetValues();
    self::assertSame('four', $values['complex_name']['complex_name2']['field_4']);

    $form->mergeValues(null);

    $values = $form->getSetValues();
    self::assertSame('four', $values['complex_name']['complex_name2']['field_4']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test mergeValues with value.
   */
  public function testMergeValues2(): void
  {
    $form = $this->setForm2();

    /** @var SimpleControl $input3 */
    $input3 = $form->getFormControlByName('field_3');
    $input3->setValue('three');

    /** @var SimpleControl $input4 */
    $input4 = $form->getFormControlByName('field_4');
    $input4->setValue('four');

    $values = $form->getSetValues();
    self::assertSame('three', $values['complex_name']['field_3']);
    self::assertSame('four', $values['complex_name']['complex_name2']['field_4']);

    $form->mergeValues(['complex_name' => ['complex_name2' => ['field_4' => '4']]]);

    $values = $form->getSetValues();
    self::assertSame('three', $values['complex_name']['field_3']);
    self::assertSame('4', $values['complex_name']['complex_name2']['field_4']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test setValues with null.
   */
  public function testSetValues1(): void
  {
    $form = $this->setForm2();

    /** @var SimpleControl $input4 */
    $input4 = $form->getFormControlByName('field_4');
    $input4->setValue('four');

    $values = $form->getSetValues();
    self::assertSame('four', $values['complex_name']['complex_name2']['field_4']);

    $form->setValues(null);

    $values = $form->getSetValues();
    self::assertNull($values['complex_name']['complex_name2']['field_4']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test setValues with value.
   */
  public function testSetValues2(): void
  {
    $form = $this->setForm2();

    /** @var SimpleControl $input3 */
    $input3 = $form->getFormControlByName('field_3');
    $input3->setValue('three');

    /** @var SimpleControl $input4 */
    $input4 = $form->getFormControlByName('field_4');
    $input4->setValue('four');

    $values = $form->getSetValues();
    self::assertSame('three', $values['complex_name']['field_3']);
    self::assertSame('four', $values['complex_name']['complex_name2']['field_4']);

    $form->setValues(['complex_name' => ['complex_name2' => ['field_4' => '4']]]);

    $values = $form->getSetValues();
    self::assertNull($values['complex_name']['field_3']);
    self::assertSame('4', $values['complex_name']['complex_name2']['field_4']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   */
  public function testSubmitValues(): void
  {
    $_POST['field_1']                                  = 'value';
    $_POST['field_3']                                  = 'value';
    $_POST['complex_name']['field_2']                  = 'value';
    $_POST['complex_name']['complex_name2']['field_4'] = 'value';

    $form    = $this->setForm2();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertArrayHasKey('field_1', $values);
    self::assertArrayHasKey('field_2', $values['complex_name']);

    self::assertArrayHasKey('field_3', $values['complex_name']);
    self::assertArrayHasKey('field_4', $values['complex_name']['complex_name2']);

    self::assertNotEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Find a control test with alphanumeric name of the control in the complex control.
   */
  public function testValid4(): void
  {
    $names = ['test01', '10', '0', '0.0'];

    foreach ($names as $name)
    {
      // Create form with control inside of complex control.
      $form = $this->setForm3($name);

      // First find complex control by name.
      /** @var ComplexControl $complexControl */
      $complexControl = $form->findFormControlByName($name);

      // Test for complex control.
      self::assertNotEmpty($complexControl);
      self::assertEquals($this->originComplexControl, $complexControl);
      self::assertEquals($name, $complexControl->getName());

      // Find control by name.
      $input = $complexControl->findFormControlByName($name);

      // Test for control.
      self::assertNotEmpty($input);
      self::assertEquals($this->originControl, $input);
      self::assertEquals($name, $input->getName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Each form controls in form must validate and add to invalid controls if it not valid.
   */
  public function testValidate(): void
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    // Create mandatory control.
    $input = new CheckboxControl('input_1');
    $input->addValidator(new MandatoryValidator());
    $fieldset->addFormControl($input);

    // Create optional control.
    $input = new CheckboxControl('input_2');
    $fieldset->addFormControl($input);

    // Create mandatory control.
    $input = new CheckboxControl('input_3');
    $input->addValidator(new MandatoryValidator());
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    // Simulate a post without any values.
    $method = $form->execute();
    $invalid = $form->getInvalidControls();

    self::assertSame('handleEchoForm', $method);
    self::assertCount(2, $invalid);
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white listed values must be loaded.
   */
  public function testWhiteList(): void
  {
    $_POST['unknown_field']                    = 'value';
    $_POST['unknown_complex']['unknown_field'] = 'value';

    $form    = $this->setForm2();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertArrayNotHasKey('unknown_field', $values);
    self::assertArrayNotHasKey('unknown_complex', $values);

    self::assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   *
   * @return RawForm
   */
  private function setForm1(): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $complex = new ComplexControl();
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);

    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);

    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('code');
    $complex->addFormControl($input);

    $input = new TextControl('state');
    $complex->addFormControl($input);

    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('zip-code');
    $complex->addFormControl($input);

    $input = new TextControl('state');
    $complex->addFormControl($input);

    $fieldset = new FieldSet('vacation');
    $form->addFieldSet($fieldset);

    $complex = new ComplexControl();
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);

    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);

    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl();
    $fieldset->addFormControl($complex);

    $input = new TextControl('street2');
    $complex->addFormControl($input);

    $input = new TextControl('city2');
    $complex->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $form->execute();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setForm2(): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $complex1 = new ComplexControl();
    $fieldset->addFormControl($complex1);

    $input = new TextControl('field_1');
    $complex1->addFormControl($input);

    $complex1 = new ComplexControl('complex_name');
    $fieldset->addFormControl($complex1);

    $input = new TextControl('field_2');
    $complex1->addFormControl($input);

    $complex2 = new ComplexControl();
    $complex1->addFormControl($complex2);

    $input = new TextControl('field_3');
    $complex2->addFormControl($input);

    $complex3 = new ComplexControl('complex_name2');
    $complex2->addFormControl($complex3);

    $input = new TextControl('field_4');
    $complex3->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $form->execute();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form with specific name of control.
   *
   * @param string $name The name of the form control.
   *
   * @return RawForm
   */
  private function setForm3(string $name): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $complex = new ComplexControl($name);
    $fieldset->addFormControl($complex);

    $input = new TextControl($name);
    $complex->addFormControl($input);

    $this->originComplexControl = $complex;
    $this->originControl        = $input;

    $form->execute();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
