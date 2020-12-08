<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Validator\LengthValidator;

/**
 * Test cases for class LengthValidator.
 */
class LengthValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests a too long string.
   */
  public function testInvalidString1(): void
  {
    $_POST['value'] = 'Hyperbolicsyllabicsesquedalymistic';
    $form           = $this->setupForm1(10, 20);

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests a too short string.
   */
  public function testInvalidString2(): void
  {
    $_POST['value'] = 'Isaac';
    $form           = $this->setupForm1(10, 20);

    self::assertFalse($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests valid string with minimum length.
   */
  public function testValidString1(): void
  {
    $_POST['value'] = 'hot';
    $form           = $this->setupForm1(3, 8);

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests valid string with maximum length.
   */
  public function testValidString2(): void
  {
    $_POST['value'] = 'buttered';
    $form           = $this->setupForm1(3, 8);

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests a valid string.
   */
  public function testValidString3(): void
  {
    $_POST['value'] = 'soul';
    $form           = $this->setupForm1(3, 8);

    self::assertTrue($form->isValid());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control which must be a valid length.
   *
   * @param int $min The minimum valid length.
   * @param int $max The maximum valid length.
   *
   * @return RawForm
   */
  private function setupForm1(int $min, int $max): RawForm
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new TextControl('value');
    $input->addValidator(new LengthValidator($min, $max));
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

