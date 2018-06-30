<?php

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
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new RadiosControl('hidden');

    self::assertSame(false, $control->isHidden());
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test special characters in the labels are replaced with HTML entities.
   */
  public function testLabels1()
  {
    $entities[] = ['id' => 0, 'name' => '<&\';">'];
    $entities[] = ['id' => 1, 'name' => '&nbsp;'];

    $input = new RadiosControl('id');
    $input->setOptions($entities, 'id', 'name', null, 'id');

    $html = $input->generate();

    self::assertContains('<label for="0">&lt;&amp;&#039;;&quot;&gt;</label>', $html);
    self::assertContains('<label for="1">&amp;nbsp;</label>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test special characters in the labels are not replaced with HTML entities.
   */
  public function testLabels2()
  {
    $entities[] = ['id' => 0, 'name' => '<span>0</span>'];
    $entities[] = ['id' => 1, 'name' => '<span>1</span>'];

    $input = new RadiosControl('id');
    $input->setOptions($entities, 'id', 'name', null, 'id');
    $input->setLabelIsHtml();

    $html = $input->generate();

    self::assertContains('<label for="0"><span>0</span></label>', $html);
    self::assertContains('<label for="1"><span>1</span></label>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A white listed value must be valid.
   */
  public function testValid1()
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
  public function testValid2()
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
  public function testWhiteListed1()
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
  private function setupForm1()
  {
    $countries[] = ['cnt_id' => '1', 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => '2', 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => '3', 'cnt_name' => 'LU'];

    $form     = new TestForm();
    $fieldset = new FieldSet('');
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
  private function setupForm2()
  {
    $countries[] = ['cnt_id' => 1, 'cnt_name' => 'NL'];
    $countries[] = ['cnt_id' => 2, 'cnt_name' => 'BE'];
    $countries[] = ['cnt_id' => 3, 'cnt_name' => 'LU'];

    $form     = new TestForm();
    $fieldset = new FieldSet('');
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
