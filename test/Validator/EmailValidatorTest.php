<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Validator;

use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Test\AbcTestCase;
use SetBased\Abc\Form\Test\TestForm;
use SetBased\Abc\Form\Validator\EmailValidator;

/**
 * Test cases for class EmailValidator.
 */
class EmailValidatorTest extends AbcTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual email address must be invalid.
   */
  public function testInvalidEmail1(): void
  {
    $_POST['email'] = '"much.more unusual"@setbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An usual email address must be invalid.
   */
  public function testInvalidEmail2(): void
  {
    $_POST['email'] = '"very.unusual.@.unusual.com"@setbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A strange but valid email address must be valid.
   */
  public function testInvalidEmail3(): void
  {
    $_POST['email'] = '!#$%&\'*+-/=?^_`{}|~@setbased.nl';
    $form           = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Localhost is not a valid domain part.
   */
  public function testInvalidEmail4(): void
  {
    $_POST['email'] = 'info@localhost';
    $form           = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only one @ is allowed outside quotation marks
   */
  public function testInvalidEmailWith2Ats1(): void
  {
    $_POST['email'] = 'info@info@setbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Only one @ is allowed outside quotation marks
   */
  public function testInvalidEmailWith2Ats2(): void
  {
    $_POST['email'] = 'info@setbased.nl@info';
    $form           = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An email address without an existing A or MX record is invalid.
   */
  public function testInvalidEmailWithNoExistantDomain(): void
  {
    $_POST['email'] = 'info@xsetbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->validate());
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

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An @ character must separate local and domain part.
   */
  public function testInvalidEmailWithoutAt(): void
  {
    $_POST['email'] = 'info.setbased.nl';
    $form           = $this->setupForm1();

    self::assertFalse($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid email address must be valid.
   */
  public function testValidEmail1(): void
  {
    $_POST['email'] = 'info@setbased.nl';
    $form           = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid email address must be valid.
   */
  public function testValidEmail2(): void
  {
    $_POST['email'] = 'p.r.water@setbased.nl';
    $form           = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A valid email address must be valid.
   */
  public function testValidEmail3(): void
  {
    $_POST['email'] = 'disposable.style.email.with+symbol@setbased.nl';
    $form           = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty email address is a valid email address.
   */
  public function testValidEmailEmpty(): void
  {
    $_POST['email'] = '';
    $form           = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty email address is a valid email address.
   */
  public function testValidEmailFalse(): void
  {
    $_POST['email'] = false;
    $form           = $this->setupForm1();

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An empty email address is a valid email address.
   */
  public function testValidEmailNull(): void
  {
    $_POST['email'] = null;
    $form           = $this->setupForm1();

    self::assertTrue($form->validate());
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

    self::assertTrue($form->validate());
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

    self::assertTrue($form->validate());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid email address) values.
   *
   * @return TestForm
   */
  private function setupForm1(): TestForm
  {
    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('email');
    $input->addValidator(new EmailValidator());
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

