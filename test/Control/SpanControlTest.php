<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\SpanControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;

/**
 * Unit tests for class SpanControl.
 */
class SpanControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new SpanControl('name');
    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $fieldset->addFormControl($input);

    $html = $form->getHtml();

    $pos = strpos($html, 'Hello<span>');
    self::assertNotEquals(false, $pos);

    $pos = strpos($html, '</span>World');
    self::assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

