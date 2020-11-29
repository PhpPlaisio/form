<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Test\TestForm;

/**
 * Unit tests for class SelectControl.
 */
class SelectControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases for setValue.
   *
   * @return array[]
   */
  public function setValueCases()
  {
    $cases = [];

    // Setting the value to null and no value is been posted must result in null for the value of the form control.
    $cases[] = ['value'     => null,
                'submitted' => null,
                'expected'  => null];

    // Setting the value to empty string and no value is been posted must result in null for the value of the form
    // control.
    $cases[] = ['value'     => '',
                'submitted' => null,
                'expected'  => null];

    // The type of the key must be returned not the type passed to setValue or the type of the submitted value.
    $cases[] = ['value'     => '2',
                'submitted' => '2',
                'expected'  => '2'];
    $cases[] = ['value'     => 2,
                'submitted' => '2',
                'expected'  => '2'];
    $cases[] = ['value'     => '2',
                'submitted' => 2,
                'expected'  => '2'];
    $cases[] = ['value'     => 2,
                'submitted' => 2,
                'expected'  => '2'];

    // The type of the key must be returned not the type passed to setValue nor the type of the submitted value.
    $cases[] = ['value'     => '4',
                'submitted' => '4',
                'expected'  => 4];
    $cases[] = ['value'     => 4,
                'submitted' => '4',
                'expected'  => 4];
    $cases[] = ['value'     => '4',
                'submitted' => 4,
                'expected'  => 4];
    $cases[] = ['value'     => 4,
                'submitted' => 4,
                'expected'  => 4];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is not marked changed when the empty value is submitted.
   */
  public function testChangedControls1(): void
  {
    $_POST['cnt_id'] = ' ';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed = $form->getChangedControls();

    self::assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is marked changed when a valid value is submitted.
   */
  public function testChangedControls2(): void
  {
    $_POST['cnt_id'] = '2';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed = $form->getChangedControls();

    self::assertNotEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is not marked changed when a none valid value is submitted.
   */
  public function testChangedControls3(): void
  {
    $_POST['cnt_id'] = '123';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $changed = $form->getChangedControls();

    self::assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is not marked changed when the none default empty value is submitted.
   */
  public function testChangedControls4(): void
  {
    $_POST['cnt_id'] = '-';

    $form = $this->setupForm1('-');
    $form->loadSubmittedValues();
    $changed = $form->getChangedControls();

    self::assertEmpty($changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values of immutable form control do not change.
   */
  public function testImmutable(): void
  {
    $_POST['cnt_id'] = '3';

    $form = $this->setupForm2();
    /** @var SelectControl $input */
    $input = $form->getFormControlByName('cnt_id');
    $input->setImmutable(true);

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEquals('1', $values['cnt_id']);
    self::assertArrayNotHasKey('cnt_id', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values of mutable form control do change.
   */
  public function testMutable(): void
  {
    $_POST['cnt_id'] = '3';

    $form = $this->setupForm2();
    /** @var SelectControl $input */
    $input = $form->getFormControlByName('cnt_id');
    $input->setMutable(true);

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEquals('3', $values['cnt_id']);
    self::assertArrayHasKey('cnt_id', $changed);
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

    $input = new SelectControl('name');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    $pos = strpos($html, 'Hello<select');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '</select>World');
    self::assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for setValue.
   *
   * @param mixed $value     The new value for the radios form control.
   * @param mixed $submitted The submitted value.
   * @param mixed $expected  The expected value.
   *
   * @dataProvider setValueCases
   */
  public function testSetValue($value, $submitted, $expected)
  {
    $_POST['cnt_id'] = $submitted;

    $countries[] = ['cnt_id' => '1', 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => '2', 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => '3', 'cnt_name' => 'LU'];
    $countries[] = ['cnt_id' => 4, 'cnt_name' => 'DE'];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new SelectControl('cnt_id');
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    $input->setValue($value);
    self::assertSame($value, $form->getSetValues()['cnt_id']);

    $form->loadSubmittedValues();

    $values = $form->getValues();
    self::assertSame($expected, $values['cnt_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid.
   */
  public function testValid1(): void
  {
    $_POST['cnt_id'] = '3';

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $values = $form->getValues();

    self::assertEquals('3', $values['cnt_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid (even whens string and integers are mixed).
   */
  public function testValid2(): void
  {
    $_POST['cnt_id'] = '3';

    $form = $this->setupForm2();
    $form->loadSubmittedValues();
    $values = $form->getValues();

    self::assertEquals('3', $values['cnt_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white listed values must be loaded.
   */
  public function testWhiteListed1(): void
  {
    // cnt_id is not a value that is in the white list of values (i.e. 1,2, and 3).
    $_POST['cnt_id'] = 99;

    $form = $this->setupForm1();
    $form->loadSubmittedValues();
    $values = $form->getValues();

    self::assertArrayHasKey('cnt_id', $values);
    self::assertNull($values['cnt_id']);
    self::assertEmpty($form->getChangedControls());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white listed values must be loaded.
   */
  public function testWhiteListed2(): void
  {
    // cnt_id is not a value that is in the white list of values (i.e. 1,2, and 3).
    $_POST['cnt_id'] = 99;

    $form = $this->setupForm2();
    $form->loadSubmittedValues();
    $values = $form->getValues();

    self::assertArrayHasKey('cnt_id', $values);
    self::assertNull($values['cnt_id']);
    self::assertArrayHasKey('cnt_id', $form->getChangedControls());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test labels are casted to strings.
   */
  public function testWithNumericValues(): void
  {
    $days[] = ['day_id' => '1', 'days' => 1];
    $days[] = ['day_id' => '2', 'days' => 2];
    $days[] = ['day_id' => '3', 'days' => 3];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new SelectControl('day_id');
    $input->setOptions($days, 'day_id', 'days');
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    self::assertNotEmpty($html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   *
   * @param string $emptyOption The value of the empty option.
   *
   * @return TestForm
   */
  private function setupForm1(string $emptyOption = ' '): TestForm
  {
    $countries[] = ['cnt_id' => '1', 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => '2', 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => '3', 'cnt_name' => 'LU'];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new SelectControl('cnt_id');
    $input->setEmptyOption($emptyOption);
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control. Difference between this function and SetupForm1 are the cnt_id are
   * integers.
   */
  private function setupForm2(): TestForm
  {
    $countries[] = ['cnt_id' => 1, 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => 2, 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => 3, 'cnt_name' => 'LU'];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new SelectControl('cnt_id');
    $input->setEmptyOption();
    $input->setValue('1');
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
