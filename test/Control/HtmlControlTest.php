<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\HtmlControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\PlaisioTestCase;
use SetBased\Helper\Cast;

/**
 * Unit tests for class HtmlControl.
 */
class HtmlControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The value of a HtmlControl must be independent of the posted values.
   */
  public function testGetValues1(): void
  {
    $_POST['snippet'] = 'bye bye';

    $html = '<h1>Hello World</h1>';

    $form = $this->setupForm1($html);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertSame($html, $values['snippet']);
    self::assertArrayNotHasKey('snippet', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test HTML generated by element.
   */
  public function testHtml(): void
  {
    $input = new HtmlControl('myInput');
    $input->setText('Hello, World!');

    $fieldSet = new FieldSet('myFieldSet');
    $fieldSet->addFormControl($input);

    $form = new RawForm('myForm');
    $form->addFieldSet($fieldSet);

    $html     = $form->htmlForm();
    $expected = '<fieldset class="frm-fieldset">Hello, World!</fieldset>';
    self::assertStringContainsString($expected, $html);
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

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertSame($html, $values['snippet']);
    self::assertArrayNotHasKey('snippet', $changed);
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

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertSame($html, $values['snippet']);
    self::assertArrayNotHasKey('snippet', $changed);
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

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertIsString($values['snippet']);
    self::assertSame(Cast::toManString(pi()), $values['snippet']);
    self::assertArrayNotHasKey('snippet', $changed);
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

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertSame('&lt;&amp;&gt;', $values['snippet']);
    self::assertArrayNotHasKey('snippet', $changed);
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

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertTrue($form->isValid());
    self::assertSame('handleSubmit', $method);
    self::assertSame($html, $values['snippet']);
    self::assertArrayNotHasKey('snippet', $changed);
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
  /**
   * Create a from with a HtmlControl.
   *
   * @param string|null $html The value of the HtmlControl.
   *
   * @return RawForm
   */
  private function setupForm1($html = null): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new HtmlControl('snippet');
    $input->setHtml($html);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
