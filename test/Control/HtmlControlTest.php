<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\HtmlControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class HtmlControl.
 */
class HtmlControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Create a from with a HtmlControl.
   *
   * @param string|null $html The value of the HtmlControl.
   *
   * @return TestForm
   */
  public function setupForm1($html = null)
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new HtmlControl('snippet');
    $input->setHtml($html);
    $fieldset->addFormControl($input);

    return $form;
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The value of a HtmlControl must be independent of the posted values.
   */
  public function testGetValues1()
  {
    $html = '<h1>Hello World</h1>';

    $form = $this->setupForm1($html);

    $_POST['snippet'] = 'bye bye';
    $form->loadSubmittedValues();

    $values = $form->getValues();

    self::assertSame($html, $values['snippet']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new HtmlControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * It must be possible to set the value of a HtmlControl by Form::mergeValues.
   */
  public function testMergeValues1()
  {
    $html = '<h1>Hello World</h1>';

    $form = $this->setupForm1();

    $values['snippet'] = $html;
    $form->mergeValues($values);

    $form->loadSubmittedValues();

    $values = $form->getValues();

    self::assertSame($html, $values['snippet']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The value of a HtmlControl must not be changes if the name of the form control is not in the values.
   */
  public function testMergeValues2()
  {
    $html = '<h1>Hello World</h1>';

    $form = $this->setupForm1($html);

    // Merge with other values.
    $values = ['name' => 'paul'];
    $form->mergeValues($values);

    $form->loadSubmittedValues();

    $values = $form->getValues();

    self::assertSame($html, $values['snippet']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Html spacial characters must be replaced with HTMl entities.
   */
  public function testSetText()
  {
    $form = $this->setupForm1();

    /** @var HtmlControl $control */
    $control = $form->getFormControlByName('snippet');
    $control->setText('<&>');

    $form->loadSubmittedValues();

    $values = $form->getValues();

    self::assertSame('&lt;&amp;&gt;', $values['snippet']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * It must be possible to set the value of a HtmlControl by Form::setValues.
   */
  public function testSetValues1()
  {
    $html = '<h1>Hello World</h1>';

    $form = $this->setupForm1('</br>');

    $values = ['snippet' => $html];
    $form->setValues($values);

    $form->loadSubmittedValues();

    $values = $form->getValues();

    self::assertSame($html, $values['snippet']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function setUp()
  {
    parent::setUp();

    $_POST = [];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
