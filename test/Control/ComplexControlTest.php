<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\CheckboxControl;
use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\SimpleControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Validator\MandatoryValidator;

/**
 * Class ButtonControlTest
 */
class ComplexControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @var ComplexControl
   */
  private $originComplexControl;

  /**
   * @var SimpleControl
   */
  private $originControl;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test find FormControl by name.
   */
  public function testFindFormControlByName()
  {
    $form = $this->setForm1();

    // Find form control by name. Must return object.
    $input = $form->findFormControlByName('street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

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
  public function testFindFormControlByPath()
  {
    $form = $this->setForm1();

    // Find form control by path. Must return object.
    $input = $form->findFormControlByPath('/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/post/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/post/zip-code');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/vacation/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/vacation/post/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->findFormControlByPath('/vacation/post/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

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
  public function testGetFormControlByName()
  {
    $form = $this->setForm1();

    // Get form control by name. Must return object.
    $input = $form->getFormControlByName('vacation');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $input->getFormControlByName('city2');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);
    self::assertEquals('city2', $input->getLocalName());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get FormControl by path.
   */
  public function testGetFormControlByPath()
  {
    $form = $this->setForm1();

    // Get form control by path. Must return object.
    $input = $form->getFormControlByPath('/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->getFormControlByPath('/post/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->getFormControlByPath('/vacation/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->getFormControlByPath('/vacation/post/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);

    $input = $form->getFormControlByPath('/vacation/post/street');
    self::assertInstanceOf('\\SetBased\\Abc\\Form\\Control\\Control', $input);
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   *
   * @expectedException \Exception
   */
  public function testGetNotExistsFormControlByName1()
  {
    $form = $this->setForm1();
    $form->getFormControlByName('not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   *
   * @expectedException \Exception
   */
  public function testGetNotExistsFormControlByName2()
  {
    $form = $this->setForm1();
    $form->getFormControlByName('/no_path/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by name what does not exist. Must trow exception.
   *
   * @expectedException \Exception
   */
  public function testGetNotExistsFormControlByName3()
  {
    $form = $this->setForm1();
    $form->getFormControlByName('/vacation/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException \Exception
   */
  public function testGetNotExistsFormControlByPath1()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException \Exception
   */
  public function testGetNotExistsFormControlByPath2()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('street');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException \Exception
   */
  public function testGetNotExistsFormControlByPath3()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('/no_path/not_exists');
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Get form control by path what does not exist. Must trow exception.
   *
   * @expectedException \Exception
   */
  public function testGetNotExistsFormControlByPath4()
  {
    $form = $this->setForm1();
    $form->getFormControlByPath('/vacation/not_exists');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new ComplexControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test setValues with null.
   */
  public function testSetValues1()
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
   */
  public function testSubmitValues()
  {
    $_POST['field_1']                                  = 'value';
    $_POST['field_3']                                  = 'value';
    $_POST['complex_name']['field_2']                  = 'value';
    $_POST['complex_name']['complex_name2']['field_4'] = 'value';

    $form = $this->setForm2();
    $form->loadSubmittedValues();

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
  public function testValid4()
  {
    $names = ['test01', 10, 0, '0.0'];

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
      self::assertEquals($name, $complexControl->getLocalName());

      // Find control by name.
      $input = $complexControl->findFormControlByName($name);

      // Test for control.
      self::assertNotEmpty($input);
      self::assertEquals($this->originControl, $input);
      self::assertEquals($name, $input->getLocalName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Each form controls in form must validate and add to invalid controls if it not valid.
   */
  public function testValidate()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
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

    // Simulate a post without any values.
    $form->loadSubmittedValues();
    $form->validate();
    $invalid = $form->getInvalidControls();

    // We expect 2 invalid controls.
    self::assertCount(2, $invalid);
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white listed values must be loaded.
   */
  public function testWhiteList()
  {
    $_POST['unknown_field']                    = 'value';
    $_POST['unknown_complex']['unknown_field'] = 'value';

    $form = $this->setForm2();
    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertArrayNotHasKey('unknown_field', $values);
    self::assertArrayNotHasKey('unknown_complex', $values);

    self::assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setForm1()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
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

    $form->prepare();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setForm2()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
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

    $form->prepare();

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
  private function setForm3($name)
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $complex = new ComplexControl($name);
    $fieldset->addFormControl($complex);

    $input = new TextControl($name);
    $complex->addFormControl($input);

    $this->originComplexControl = $complex;
    $this->originControl        = $input;

    $form->prepare();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
