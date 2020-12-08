<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Validator\EmailValidator;

/**
 * Test cases for class EmailValidator.
 */
class EmailValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual email address must be invalid.
   */
  public function testInvalidEmail1(): void
  {
    $_POST['email'] = '"much.more unusual"@setbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual email address must be invalid.
   */
  public function testInvalidEmail2(): void
  {
    $_POST['email'] = '"very.unusual.@.unusual.com"@setbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A strange but valid email address must be valid.
   */
  public function testInvalidEmail3(): void
  {
    $_POST['email'] = '!#$%&\'*+-/=?^_`{}|~@setbased.nl';
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Localhost is not a valid domain part.
   */
  public function testInvalidEmail4(): void
  {
    $_POST['email'] = 'info@localhost';
    $form           = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only one @ is allowed outside quotation marks
   */
  public function testInvalidEmailWith2Ats1(): void
  {
    $_POST['email'] = 'info@info@setbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only one @ is allowed outside quotation marks
   */
  public function testInvalidEmailWith2Ats2(): void
  {
    $_POST['email'] = 'info@setbased.nl@info';
    $form           = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An email address without an existing A or MX record is invalid.
   */
  public function testInvalidEmailWithNoExistantDomain(): void
  {
    $_POST['email'] = 'info@xsetbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An email address with a to long local part must be invalid. The maximum length of the local part is 64 characters,
   * see http://en.wikipedia.org/wiki/Email_address.
   */
  public function testInvalidEmailWithToLongLocalPart(): void
  {
    $local          = str_repeat('x', 65);
    $_POST['email'] = "$local@setbased.nl";
    $form           = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An @ character must separate local and domain part.
   */
  public function testInvalidEmailWithoutAt(): void
  {
    $_POST['email'] = 'info.setbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid email address must be valid.
   */
  public function testValidEmail1(): void
  {
    $_POST['email'] = 'info@setbased.nl';
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid email address must be valid.
   */
  public function testValidEmail2(): void
  {
    $_POST['email'] = 'p.r.water@setbased.nl';
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid email address must be valid.
   */
  public function testValidEmail3(): void
  {
    $_POST['email'] = 'disposable.style.email.with+symbol@setbased.nl';
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty email address is a valid email address.
   */
  public function testValidEmailEmpty(): void
  {
    $_POST['email'] = '';
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty email address is a valid email address.
   */
  public function testValidEmailFalse(): void
  {
    $_POST['email'] = false;
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty email address is a valid email address.
   */
  public function testValidEmailNull(): void
  {
    $_POST['email'] = null;
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An email address with a long domain part must be valid. The maximum length of the domain part is 255 characters,
   * see http://en.wikipedia.org/wiki/Email_address.
   */
  public function testValidEmailWithLongDomain(): void
  {
    $_POST['email'] = 'info@thelongestdomainnameintheworldandthensomeandthensomemoreandmore.com';
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An email address with a long local part must be valid. The maximum length of the local part is 64 characters,
   * see http://en.wikipedia.org/wiki/Email_address.
   */
  public function testValidEmailWithLongLocalPart(): void
  {
    $local          = str_repeat('x', 64);
    $_POST['email'] = "$local@setbased.nl";
    $form           = $this->setupForm1();

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid email address) values.
   *
   * @return RawForm
   */
  private function setupForm1(): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('email');
    $input->addValidator(new EmailValidator());
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $form->execute();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

