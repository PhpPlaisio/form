<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Validator\HttpValidator;

/**
 * Test cases for class HttpValidator.
 */
class HttpValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual url address must be invalid.
   */
  public function testInvalidHttp1(): void
  {
    $_POST['url'] = 'hffd//:www.setbased/nl';
    $form         = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual url address must be invalid.
   */
  public function testInvalidHttp2(): void
  {
    $_POST['url'] = 'http//golgelinva';
    $form         = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A strange but valid url address must be valid.
   */
  public function testInvalidHttp3(): void
  {
    $_POST['url'] = 'ftp//:!#$%&\'*+-/=?^_`{}|~ed.com';
    $form         = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid url address must be valid.
   */
  public function testValidHttp(): void
  {
    $_POST['url'] = 'http://www.setbased.nl';
    $form         = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid url address must be valid.
   */
  public function testValidHttp2(): void
  {
    $_POST['url'] = 'http://www.google.com';
    $form         = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid url address must be valid.
   */
  public function testValidHttp3(): void
  {
    $_POST['url'] = 'http://www.php.net';
    $form         = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty url address is a valid url address.
   */
  public function testValidHttpEmpty(): void
  {
    $_POST['url'] = '';
    $form         = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty url address is a valid url address.
   */
  public function testValidHttpFalse(): void
  {
    $_POST['url'] = false;
    $form         = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty url address is a valid url address.
   */
  public function testValidHttpNull(): void
  {
    $_POST['url'] = null;
    $form         = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid url address).
   *
   * @return RawForm
   */
  private function setupForm1(): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('url');
    $input->addValidator(new HttpValidator());
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $form->execute();

    return $form;
  }

  //-------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

