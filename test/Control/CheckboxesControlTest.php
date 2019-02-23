<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\CheckboxesControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class CheckboxesControl.
 */
class CheckboxesControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Option with index 0 (string or int) equals 0 (string or int) and not equals 0.0 (string).
   */
  public function testEmptyValues1(): void
  {
    $_POST['cnt_id']['0'] = 'on';

    $form   = $this->setupForm3();
    $values = $form->getValues();

    // Test checkbox with index '0' has been checked.
    self::assertTrue($values['cnt_id']['0']);

    // Test checkbox with index '0.0' has not been checked.
    self::assertFalse($values['cnt_id']['0.0']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Option with index 0 (string or int) equals 0 (string or int) and not equals '0.0' (string).
   */
  public function testEmptyValues2(): void
  {
    $_POST['cnt_id'][0] = 'on';

    $form   = $this->setupForm3();
    $values = $form->getValues();

    // Test checkbox with index '0' has been checked.
    self::assertTrue($values['cnt_id']['0']);

    // Test checkbox with index '0.0' has not been checked.
    self::assertFalse($values['cnt_id']['0.0']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Option with index '0.0' (string) equals '0.0' (string) and not equals 0 (string or int).
   */
  public function testEmptyValues3(): void
  {
    $_POST['cnt_id']['0.0'] = 'on';

    $form   = $this->setupForm3();
    $values = $form->getValues();

    // Test checkbox with index '0' has not been checked.
    self::assertFalse($values['cnt_id']['0']);

    // Test checkbox with index '0.0' has been checked.
    self::assertTrue($values['cnt_id']['0.0']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Option with index 0 (string or int) equals 0 (string or int) and not equals '1' (string or int).
   */
  public function testEmptyValues4(): void
  {
    $_POST['cnt_id']['0'] = 'on';

    $form   = $this->setupForm4();
    $values = $form->getValues();

    // Test checkbox with index '0' has been checked.
    self::assertTrue($values['cnt_id']['0']);

    // Test checkbox with index '1' has not been checked.
    self::assertFalse($values['cnt_id']['1']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Option with index 0 (string or int) equals 0 (string or int) and not equals '1' (string or int).
   */
  public function testEmptyValues5(): void
  {
    $_POST['cnt_id'][0] = 'on';

    $form   = $this->setupForm4();
    $values = $form->getValues();

    // Test checkbox with index '0' has been checked.
    self::assertTrue($values['cnt_id']['0']);

    // Test checkbox with index '0.0' has not been checked.
    self::assertFalse($values['cnt_id']['1']);
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

    $input = new CheckboxesControl('traffic-light');
    $input->setInputAttributesMap(['xxx' => 'id', 'extra' => 'class']);
    $input->setOptions($entities, 'key', 'label');

    $html = $input->getHtml();

    self::assertStringContainsString('<input id="123" class="blink" type="checkbox" name="[O]"/>', $html);
    self::assertStringContainsString('<label for="123">Orange</label>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new CheckboxesControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test special characters in the labels are replaced with HTML entities.
   */
  public function testLabelAttributesMap(): void
  {
    $entities[] = ['key' => 'R', 'label' => 'Red'];
    $entities[] = ['key' => 'O', 'label' => 'Orange', 'extra' => 'blink', 'xxx' => 123];
    $entities[] = ['key' => 'G', 'label' => 'Green'];

    $input = new CheckboxesControl('traffic-light');
    $input->setInputAttributesMap(['xxx' => 'id']);
    $input->setLabelAttributesMap(['extra' => 'class']);
    $input->setOptions($entities, 'key', 'label');

    $html = $input->getHtml();

    self::assertStringContainsString('<input id="123" type="checkbox" name="[O]"/>', $html);
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

    $input = new CheckboxesControl('id');
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

    $input = new CheckboxesControl('id');
    $input->setInputAttributesMap(['no' => 'id']);
    $input->setOptions($entities, 'key', 'name');
    $input->setLabelIsHtml();

    $html = $input->getHtml();

    self::assertStringContainsString('<label for="0"><span>0</span></label>', $html);
    self::assertStringContainsString('<label for="1"><span>1</span></label>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test checkboxes are set of unset when was submitted.
   */
  public function testPreserveSubmitValues(): void
  {
    // Simulate submitted values.
    $_POST['cnt_id'] = ['0' => 'on', '2' => 'on', '3' => 'on'];

    $countries[] = ['cnt_id' => 0, 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => 1, 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => 2, 'cnt_name' => 'LU'];
    $countries[] = ['cnt_id' => 3, 'cnt_name' => 'DE'];
    $countries[] = ['cnt_id' => 4, 'cnt_name' => 'GB'];

    // Create a form with checkboxes.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxesControl('cnt_id');
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    // Generate HTML code for the form.
    $html = $form->getHtml();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Asset that the checkboxes are set or unset according to the $values.
    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[0]' and @type='checkbox' and @checked='checked']");
    self::assertEquals(1, $list->length);

    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[1]' and @type='checkbox' and not(@checked)]");
    self::assertEquals(1, $list->length);

    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[2]' and @type='checkbox' and @checked='checked']");
    self::assertEquals(1, $list->length);

    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[3]' and @type='checkbox' and @checked='checked']");
    self::assertEquals(1, $list->length);

    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[4]' and @type='checkbox' and not(@checked)]");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test checkboxes are set or unset correctly with values().
   */
  public function testSetValues2(): void
  {
    $countries[] = ['cnt_id' => 0, 'cnt_name' => 'NL', 'checked' => true];
    $countries[] = ['cnt_id' => 1, 'cnt_name' => 'BE', 'checked' => true];
    $countries[] = ['cnt_id' => 2, 'cnt_name' => 'LU'];
    $countries[] = ['cnt_id' => 3, 'cnt_name' => 'DE'];
    $countries[] = ['cnt_id' => 4, 'cnt_name' => 'GB'];

    // Create a form with checkboxes.
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxesControl('cnt_id');
    $input->setOptions($countries, 'cnt_id', 'cnt_name', 'checked');
    $fieldset->addFormControl($input);

    // Set the values of the checkboxes.
    $values['cnt_id'][0] = true;
    $values['cnt_id'][1] = false;
    $values['cnt_id'][2] = true;
    $values['cnt_id'][3] = true;
    $values['cnt_id'][4] = false;

    $form->setValues($values);

    // Generate HTML code for the form.
    $form = $form->getHtml();

    $doc = new \DOMDocument();
    $doc->loadXML($form);
    $xpath = new \DOMXpath($doc);

    // Asset that the checkboxes are set or unset according to the $values.
    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[0]' and @type='checkbox' and @checked='checked']");
    self::assertEquals(1, $list->length);

    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[1]' and @type='checkbox' and not(@checked)]");
    self::assertEquals(1, $list->length);

    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[2]' and @type='checkbox' and @checked='checked']");
    self::assertEquals(1, $list->length);

    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[3]' and @type='checkbox' and @checked='checked']");
    self::assertEquals(1, $list->length);

    $list = $xpath->query("/form/fieldset/span/input[@name='cnt_id[4]' and @type='checkbox' and not(@checked)]");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a check/unchecked checkboxes are added correctly to the values.
   */
  public function testSubmittedValues1(): void
  {
    $_POST['cnt_id']['2'] = 'on';

    $form   = $this->setupForm1();
    $values = $form->getValues();

    // Test checkbox with index 2 has been checked.
    self::assertTrue($values['cnt_id']['2']);

    // Test checkbox with index 1 has not been checked.
    self::assertFalse($values['cnt_id']['1']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a check/unchecked checkboxes are added correctly to the values.
   */
  public function testSubmittedValues2(): void
  {
    $_POST['cnt_id']['0.1'] = 'on';

    $form   = $this->setupForm1();
    $values = $form->getValues();

    // Test checkbox with index 0.1 has been checked.
    self::assertTrue($values['cnt_id']['0.1']);

    // Test checkbox with index 1 has not been checked.
    self::assertFalse($values['cnt_id']['1']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a check/unchecked checkboxes are added correctly to the values.
   */
  public function testSubmittedValues3a(): void
  {
    $_POST['cnt_id']['2'] = 'on';

    $form   = $this->setupForm1();
    $values = $form->getValues();

    // Test checkbox with index 2 has been checked.
    self::assertTrue($values['cnt_id']['2']);

    // Test checkbox with index 1 has not been checked.
    self::assertFalse($values['cnt_id']['1']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a check/unchecked checkboxes are added correctly to the values with alternative values.
   */
  public function testSubmittedValues3b(): void
  {
    $_POST['cnt_id']['2'] = 'on';

    $form   = $this->setupForm1();
    $values = $form->getValues();

    // Test checkbox with index 2 has been checked.
    self::assertSame(true, $values['cnt_id']['2']);

    // Test checkbox with index 1 has not been checked.
    self::assertSame(false, $values['cnt_id']['1']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a check/unchecked checkboxes are added correctly to the values.
   */
  public function testSubmittedValues4(): void
  {
    $_POST['cnt_id']['0.1'] = 'on';

    $form   = $this->setupForm1();
    $values = $form->getValues();

    // Test checkbox with index 0.1 has been checked.
    self::assertTrue($values['cnt_id']['0.1']);

    // Test checkbox with index 1 has not been checked.
    self::assertFalse($values['cnt_id']['1']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only white listed values must be loaded.
   */
  public function testWhiteListed1(): void
  {
    // cnt_id is not a value that is in the white list of values (i.e. 1,2, and 3).
    $_POST['cnt_id']['99'] = 'on';

    $form    = $this->setupForm1();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertArrayNotHasKey('cnt_id', $changed);
    self::assertArrayNotHasKey('99', $values['cnt_id']);
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

    $input = new CheckboxesControl('day_id');
    $input->setOptions($days, 'day_id', 'days');
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    self::assertNotEmpty($html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setupForm1(): TestForm
  {
    $countries[] = ['cnt_id' => '0', 'cnt_name' => '-'];
    $countries[] = ['cnt_id' => '1', 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => '2', 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => '3', 'cnt_name' => 'LU'];
    $countries[] = ['cnt_id' => '0.1', 'cnt_name' => 'UA'];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxesControl('cnt_id');
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setupForm3(): TestForm
  {
    $countries[] = ['cnt_id' => '0', 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => '0.0', 'cnt_name' => 'BE'];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxesControl('cnt_id');
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a select form control.
   */
  private function setupForm4(): TestForm
  {
    $countries[] = ['cnt_id' => 0, 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => 1, 'cnt_name' => 'BE'];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new CheckboxesControl('cnt_id');
    $input->setOptions($countries, 'cnt_id', 'cnt_name');
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
