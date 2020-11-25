<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\HtmlControl;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Test\TestForm;
use SetBased\Helper\Cast;

/**
 * Unit tests for class HtmlControl.
 */
class HtmlControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Create a from with a HtmlControl.
   *
   * @param string|null $html The value of the HtmlControl.
   *
   * @return TestForm
   */
  public function setupForm1($html = null): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
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
  public function testGetValues1(): void
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
  public function testIsHidden(): void
  {
    $control = new HtmlControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * It must be possible to set the value of a HtmlControl by Form::mergeValues.
   */
  public function testMergeValues1(): void
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
  public function testMergeValues2(): void
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
   * The value of a HtmlControl must be converted to a string.
   */
  public function testMergeValues3(): void
  {
    $html = '<h1>Hello World</h1>';

    $form = $this->setupForm1($html);

    // Merge with other values.
    $values = ['snippet' => pi()];
    $form->mergeValues($values);

    $form->loadSubmittedValues();

    $values = $form->getValues();

    self::assertIsString($values['snippet']);
    self::assertSame(Cast::toManString(pi()), $values['snippet']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Html spacial characters must be replaced with HTMl entities.
   */
  public function testSetText(): void
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
  public function testSetValues1(): void
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
  protected function setUp(): void
  {
    parent::setUp();

    $_POST = [];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
