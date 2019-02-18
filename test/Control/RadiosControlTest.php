<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\RadiosControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class RadiosControl.
 */
class RadiosControlTest extends AbcTestCase
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
   * Test special characters in the labels are replaced with HTML entities.
   */
  public function testInputAttributesMap(): void
  {
    $entities[] = ['key' => 'R', 'label' => 'Red'];
    $entities[] = ['key' => 'O', 'label' => 'Orange', 'extra' => 'blink', 'xxx' => 123];
    $entities[] = ['key' => 'G', 'label' => 'Green'];

    $input = new RadiosControl('traffic-light');
    $input->setInputAttributesMap(['xxx' => 'id', 'extra' => 'class']);
    $input->setOptions($entities, 'key', 'label');

    $html = $input->getHtml();

    self::assertStringContainsString('<input id="123" class="blink" type="radio" value="O"/>', $html);
    self::assertStringContainsString('<label for="123">Orange</label>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new RadiosControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test special characters in the labels are replaced with HTML entities.
   */
  public function testLabelAttributesMap()
  {
    $entities[] = ['key' => 'R', 'label' => 'Red'];
    $entities[] = ['key' => 'O', 'label' => 'Orange', 'extra' => 'blink', 'xxx' => 123];
    $entities[] = ['key' => 'G', 'label' => 'Green'];

    $input = new RadiosControl('traffic-light');
    $input->setInputAttributesMap(['xxx' => 'id']);
    $input->setLabelAttributesMap(['extra' => 'class']);
    $input->setOptions($entities, 'key', 'label');

    $html = $input->getHtml();

    self::assertStringContainsString('<input id="123" type="radio" value="O"/>', $html);
    self::assertStringContainsString('<label class="blink" for="123">Orange</label>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test special characters in the labels are replaced with HTML entities.
   */
  public function testLabels1(): void
  {
    $entities[] = ['no' => 0, 'key' => 'A', 'name' => '<&\';">'];
    $entities[] = ['no' => 1, 'key' => 'B', 'name' => '&nbsp;'];

    $input = new RadiosControl('id');
    $input->setInputAttributesMap(['no' => 'id']);
    $input->setOptions($entities, 'key', 'name');

    $html = $input->getHtml();

    self::assertStringContainsString('<label for="0">&lt;&amp;&#039;;&quot;&gt;</label>', $html);
    self::assertStringContainsString('<label for="1">&amp;nbsp;</label>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test special characters in the labels are not replaced with HTML entities.
   */
  public function testLabels2(): void
  {
    $entities[] = ['no' => 0, 'key' => 'A', 'name' => '<span>0</span>'];
    $entities[] = ['no' => 1, 'key' => 'B', 'name' => '<span>1</span>'];

    $input = new RadiosControl('id');
    $input->setInputAttributesMap(['no' => 'id']);
    $input->setOptions($entities, 'key', 'name');
    $input->setLabelIsHtml();

    $html = $input->getHtml();

    self::assertStringContainsString('<label for="0"><span>0</span></label>', $html);
    self::assertStringContainsString('<label for="1"><span>1</span></label>', $html);
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

    $input = new RadiosControl('cnt_id');
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

    $form   = $this->setupForm1();
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

    $form   = $this->setupForm2();
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

    $form   = $this->setupForm1();
    $values = $form->getValues();

    self::assertArrayHasKey('cnt_id', $values);
    self::assertNull($values['cnt_id']);
    self::assertEmpty($form->getChangedControls());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setupForm1(): TestForm
  {
    $countries[] = ['cnt_id' => '1', 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => '2', 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => '3', 'cnt_name' => 'LU'];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new RadiosControl('cnt_id');
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control. Difference between this function
   * and SetupForm1 are the cnt_id are integers.
   */
  private function setupForm2(): TestForm
  {
    $countries[] = ['cnt_id' => 1, 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => 2, 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => 3, 'cnt_name' => 'LU'];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new RadiosControl('cnt_id');
    $input->setValue('1');
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}
