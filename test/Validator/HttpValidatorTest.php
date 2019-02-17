<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Validator;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;
use SetBased\Abc\Form\Validator\HttpValidator;

/**
 * Test cases for class HttpValidator.
 */
class HttpValidatorTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual url address must be invalid.
   */
  public function testInvalidHttp1(): void
  {
    $_POST['url'] = 'hffd//:www.setbased/nl';
    $form         = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual url address must be invalid.
   */
  public function testInvalidHttp2(): void
  {
    $_POST['url'] = 'http//golgelinva';
    $form         = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A strange but valid url address must be valid.
   */
  public function testInvalidHttp3(): void
  {
    $_POST['url'] = 'ftp//:!#$%&\'*+-/=?^_`{}|~ed.com';
    $form         = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid url address must be valid.
   */
  public function testValidHttp(): void
  {
    $_POST['url'] = 'http://www.setbased.nl';
    $form         = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid url address must be valid.
   */
  public function testValidHttp2(): void
  {
    $_POST['url'] = 'http://www.google.com';
    $form         = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid url address must be valid.
   */
  public function testValidHttp3(): void
  {
    $_POST['url'] = 'http://www.php.net';
    $form         = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty url address is a valid url address.
   */
  public function testValidHttpEmpty(): void
  {
    $_POST['url'] = '';
    $form         = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty url address is a valid url address.
   */
  public function testValidHttpFalse(): void
  {
    $_POST['url'] = false;
    $form         = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty url address is a valid url address.
   */
  public function testValidHttpNull(): void
  {
    $_POST['url'] = null;
    $form         = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid url address).
   *
   * @return TestForm
   */
  private function setupForm1(): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet('');
    $form->addFieldSet($fieldset);

    $input = new TextControl('url');
    $input->addValidator(new HttpValidator());
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //-------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

