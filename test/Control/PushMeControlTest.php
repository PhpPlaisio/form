<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\PushMeControl;
use SetBased\Abc\Form\RawForm;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class PushMeControlTest
 */
abstract class PushMeControlTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testPrefixAndPostfix()
  {
    $form     = new RawForm();
    $fieldset = $form->addFieldSet(new FieldSet());

    $input = $this->getControl('name');
    $fieldset->addFormControl($input);

    $input->setPrefix('Hello');
    $input->setPostfix('World');
    $form->prepare();
    $form->prepare();
    $html = $form->generate();

    $pos = strpos($html, 'Hello<input');
    $this->assertNotEquals(false, $pos);

    $pos = strpos($html, '/>World');
    $this->assertNotEquals(false, $pos);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Method SetValue() has no effect for buttons.
   */
  public function testSetValues()
  {
    // Create form.
    $form     = new RawForm();
    $fieldset = $form->addFieldSet(new FieldSet());

    $input = $this->getControl('button');
    $input->setValue("Do not push");
    $fieldset->addFormControl($input);

    // Set the values for button.
    $values['button'] = 'Push';
    $form->setValues($values);

    // Generate HTML.
    $form->prepare();
    $html = $form->generate();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Names of buttons must be absolute setValue has no effect for buttons.
    $list = $xpath->query("/form/fieldset/input[@name='button' and @value='Do not push' and @type='".$this->getControlType()."']");
    $this->assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a concrete instance of PushMeControl.
   *
   * @param string $name The of the control.
   *
   * @return PushMeControl
   */
  abstract protected function getControl($name);

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return type of form control.
   *
   * @return string
   */
  abstract protected function getControlType();

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
