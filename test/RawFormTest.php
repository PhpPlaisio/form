<?php
declare(strict_types=1);

namespace Plaisio\Form\Test;

use Plaisio\Form\Control\ButtonControl;
use Plaisio\Form\Control\CheckboxesControl;
use Plaisio\Form\Control\ComplexControl;
use Plaisio\Form\Control\Control;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\HiddenSubmitControl;
use Plaisio\Form\Control\SubmitControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\RawForm;

/**
 * Test cases for class RawForm.
 */
class RawFormTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Base test for testing searchSubmitHandler.
   *
   * @param Control     $trigger  The control that will trigger the form submit.
   * @param string|null $expected The expected value.
   */
  public function searchSubmitHandlerTest(Control $trigger, ?string $expected): void
  {
    $form      = new TestForm('form1');
    $fieldset1 = new FieldSet('fieldset1');
    $form->addFieldSet($fieldset1);

    $complex1 = new ComplexControl('complex1');
    $fieldset1->addFormControl($complex1);

    $input1 = new TextControl('name1');
    $complex1->addFormControl($input1);

    $complex1->addFormControl($trigger);

    $_POST['form1']['fieldset1']['complex1']['button1'] = 'knob';

    $handler = $form->execute();

    self::assertSame($expected, $handler);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a complex control with different types of names.
   */
  public function testFindComplexControl(): void
  {
    $names = ['hello', '10', '0', '0.0'];

    foreach ($names as $name)
    {
      $form = $this->setupFormFind('', $name);

      $input = $form->findFormControlByName($name);
      self::assertNotEmpty($input);
      self::assertEquals($name, $input->getName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a fieldset with different types of names.
   */
  public function testFindFieldSet(): void
  {
    $names = ['hello', '10', '0', '0.0'];

    foreach ($names as $name)
    {
      $form = $this->setupFormFind($name);

      $input = $form->findFormControlByName($name);
      self::assertNotEmpty($input);
      self::assertEquals($name, $input->getName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for finding a complex with with different types of names.
   */
  public function testFindSimpleControl(): void
  {
    $names = ['hello', '10', '0', '0.0'];

    foreach ($names as $name)
    {
      $form = $this->setupFormFind('', 'post', $name);

      $input = $form->findFormControlByName($name);
      self::assertNotEmpty($input);
      self::assertEquals($name, $input->getName());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for getCurrentValues values.
   */
  public function testGetSetValues(): void
  {
    $options   = [];
    $options[] = ['id' => 1, 'label' => 'label1'];
    $options[] = ['id' => 2, 'label' => 'label2'];
    $options[] = ['id' => 3, 'label' => 'label3'];

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('name1');
    $fieldset->addFormControl($input);

    $input = new TextControl('name2');
    $fieldset->addFormControl($input);

    $input = new CheckboxesControl('options');
    $input->setOptions($options, 'id', 'label');
    $fieldset->addFormControl($input);

    $values['name1']      = 'name1';
    $values['name2']      = 'name2';
    $values['options'][1] = true;
    $values['options'][2] = false;
    $values['options'][3] = true;

    // Set the form control values.
    $form->setValues($values);

    $current = $form->getSetValues();

    self::assertEquals('name1', $current['name1']);
    self::assertEquals('name2', $current['name2']);
    self::assertTrue($current['options'][1]);
    self::assertFalse($current['options'][2]);
    self::assertTrue($current['options'][3]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars1(): void
  {
    $_POST = [];

    $form = $this->setupForm1();
    $form->execute();
    $changed     = $form->getChangedControls();
    $has_scalars = $form::hasScalars($changed);

    self::assertFalse($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars2(): void
  {
    $_POST           = [];
    $_POST['name1']  = 'Hello world';
    $_POST['submit'] = 'submit';

    $form = $this->setupForm1();
    $form->execute();
    $changed     = $form->getChangedControls();
    $has_scalars = $form::hasScalars($changed);

    self::assertTrue($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars3(): void
  {
    $_POST              = [];
    $_POST['name1']     = 'Hello world';
    $_POST['option'][2] = 'on';
    $_POST['submit']    = 'submit';

    $form = $this->setupForm1();
    $form->execute();
    $changed     = $form->getChangedControls();
    $has_scalars = $form::hasScalars($changed);

    self::assertTrue($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Test method hasScalars
   */
  public function testHasScalars4(): void
  {
    $_POST              = [];
    $_POST['option'][2] = 'on';
    $_POST['submit']    = 'submit';

    $form = $this->setupForm1();
    $form->execute();
    $changed     = $form->getChangedControls();
    $has_scalars = $form::hasScalars($changed);

    self::assertFalse($has_scalars);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for merging values.
   */
  public function testMergeValues(): void
  {
    $options   = [];
    $options[] = ['id' => 1, 'label' => 'label1'];
    $options[] = ['id' => 2, 'label' => 'label2'];
    $options[] = ['id' => 3, 'label' => 'label3'];

    $form     = new TestForm();
    $fieldset = new FieldSet('name');
    $form->addFieldSet($fieldset);

    $input = new TextControl('name1');
    $fieldset->addFormControl($input);

    $input = new TextControl('name2');
    $fieldset->addFormControl($input);

    $input = new CheckboxesControl('options');
    $input->setOptions($options, 'id', 'label');
    $fieldset->addFormControl($input);

    $values['name']['name1']      = 'name1';
    $values['name']['name2']      = 'name2';
    $values['name']['options'][1] = true;
    $values['name']['options'][2] = false;
    $values['name']['options'][3] = true;

    $merge['name']['name1']      = 'NAME1';
    $merge['name']['options'][2] = true;
    $merge['name']['options'][3] = null;

    // Set the form control values.
    $form->setValues($values);

    // Override few form control values.
    $form->mergeValues($merge);

    // Generate HTML.
    $html = $form->getHtml();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // name[name1] must be overridden.
    $list = $xpath->query("/form/fieldset/input[@name='name[name1]' and @value='NAME1']");
    self::assertEquals(1, $list->length);

    // name[name2] must be unchanged.
    $list = $xpath->query("/form/fieldset/input[@name='name[name2]' and @value='name2']");
    self::assertEquals(1, $list->length);

    // name[options][1] must be unchanged.
    $list = $xpath->query("/form/fieldset/span/input[@name='name[options][1]' and @checked='checked']");
    self::assertEquals(1, $list->length);

    // name[options][2] must be changed.
    $list = $xpath->query("/form/fieldset/span/input[@name='name[options][2]' and @checked='checked']");
    self::assertEquals(1, $list->length);

    // name[options][3] must be changed.
    $list = $xpath->query("/form/fieldset/span/input[@name='name[options][3]' and not(@checked)]");
    self::assertEquals(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with SubmitControl.
   */
  public function testSearchSubmitHandler01(): void
  {
    $trigger = new SubmitControl('button1');
    $trigger->setValue('knob');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ButtonControl.
   */
  public function testSearchSubmitHandler02(): void
  {
    $trigger = new ButtonControl('button1');
    $trigger->setValue('knob');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with HiddenSubmitControl.
   */
  public function testSearchSubmitHandler03()
  {
    $trigger = new HiddenSubmitControl('button1');
    $trigger->setValue('knob');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ForceSubmitControl.
   */
  public function testSearchSubmitHandler04(): void
  {
    $trigger = new ForceSubmitControl('button1', true);
    $trigger->setValue('knob');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with SubmitControl.
   */
  public function testSearchSubmitHandler11(): void
  {
    $trigger = new SubmitControl('button1');
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handleEchoForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ButtonControl.
   */
  public function testSearchSubmitHandler12(): void
  {
    $trigger = new ButtonControl('button1');
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handleEchoForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with HiddenSubmitControl.
   */
  public function testSearchSubmitHandler13(): void
  {
    $trigger = new HiddenSubmitControl('button1');
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handleEchoForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ForceSubmitControl.
   */
  public function testSearchSubmitHandler14a(): void
  {
    $trigger = new ForceSubmitControl('button1', false);
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handleEchoForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ForceSubmitControl.
   */
  public function testSearchSubmitHandler14b(): void
  {
    $trigger = new ForceSubmitControl('button1', true);
    $trigger->setValue('door');
    $trigger->setMethod('handler');

    $this->searchSubmitHandlerTest($trigger, 'handler');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with SubmitControl without setting submit handler method.
   */
  public function testSearchSubmitHandler21(): void
  {
    $this->expectException(\LogicException::class);

    $trigger = new SubmitControl('button1');
    $trigger->setValue('knob');

    $this->searchSubmitHandlerTest($trigger, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ButtonControl without setting submit handler method.
   */
  public function testSearchSubmitHandler22(): void
  {
    $this->expectException(\LogicException::class);

    $trigger = new ButtonControl('button1');
    $trigger->setValue('knob');

    $this->searchSubmitHandlerTest($trigger, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with HiddenSubmitControl without setting submit handler method.
   */
  public function testSearchSubmitHandler23(): void
  {
    $this->expectException(\LogicException::class);

    $trigger = new HiddenSubmitControl('button1');
    $trigger->setValue('knob');

    $this->searchSubmitHandlerTest($trigger, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for testing searchSubmitHandler with ForceSubmitControl without setting submit handler method.
   */
  public function testSearchSubmitHandler24(): void
  {
    $this->expectException(\LogicException::class);

    $trigger = new ForceSubmitControl('button1', true);
    $trigger->setValue('knob');

    $this->searchSubmitHandlerTest($trigger, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @return TestForm
   */
  private function setupForm1(): TestForm
  {
    $options   = [];
    $options[] = ['id' => 1, 'label' => 'label1'];
    $options[] = ['id' => 2, 'label' => 'label2'];
    $options[] = ['id' => 2, 'label' => 'label3'];

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);
    $input = new TextControl('name1');
    $fieldset->addFormControl($input);

    $input = new TextControl('name2');
    $fieldset->addFormControl($input);

    $input = new CheckboxesControl('options');
    $input->setOptions($options, 'id', 'label');
    $fieldset->addFormControl($input);

    $input = new SubmitControl('submit');
    $input->setValue('submit');
    $input->setMethod('handler');
    $fieldset->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $fieldSetName
   * @param string $complexControlName
   * @param string $textControlName
   *
   * @return RawForm
   */
  private function setupFormFind(string $fieldSetName = 'vacation',
                                 string $complexControlName = 'post',
                                 string $textControlName = 'city'): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet($fieldSetName);
    $form->addFieldSet($fieldset);

    $complex = new ComplexControl();
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);
    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);
    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl('post');
    $fieldset->addFormControl($complex);

    $input = new TextControl('zip-code');
    $complex->addFormControl($input);
    $input = new TextControl('state');
    $complex->addFormControl($input);

    $fieldset = new FieldSet('vacation');
    $form->addFieldSet($fieldset);

    $complex = new ComplexControl();
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);
    $input = new TextControl('city');
    $complex->addFormControl($input);

    $complex = new ComplexControl($complexControlName);
    $fieldset->addFormControl($complex);

    $input = new TextControl('street');
    $complex->addFormControl($input);
    $input = new TextControl($textControlName);
    $complex->addFormControl($input);

    $complex = new ComplexControl();
    $fieldset->addFormControl($complex);

    $input = new TextControl('street2');
    $complex->addFormControl($input);
    $input = new TextControl('city2');
    $complex->addFormControl($input);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
