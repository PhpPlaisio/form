<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\MultipleFileControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class MultipleFileControl.
 */
class MultipleFileControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new MultipleFileControl('hidden');

    self::assertSame(false, $control->isHidden());
  }
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new MultipleFileControl('name');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    $pos = strpos($html, 'Hello<input');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    self::assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

