<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Control\DateControl;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Form\Test\TestForm;
use Plaisio\Form\Validator\DateValidator;

/**
 * Test cases for class DateValidator.
 */
class DateValidatorTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against invalid dates.
   */
  public function testInvalidDates(): void
  {
    $dates = ['15- 7 -20', '10-april-1966', 'Hello world.', '15- 7 -20', '2015-02-29'];

    foreach ($dates as $date)
    {
      $_POST['date'] = $date;
      $form          = $this->setupForm1();

      self::assertFalse($form->validate(), "Submitted: $date");
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against valid dates.
   */
  public function testValidDates(): void
  {
    $dates = ['1966-04-10', '1970-01-01', '1900-01-01', '9999-12-31', '2020-02-29'];

    foreach ($dates as $date)
    {
      $_POST['date'] = $date;
      $form          = $this->setupForm1();

      self::assertTrue($form->validate(), "Submitted: $date");
    }
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

    $input = new DateControl('date');
    $input->addValidator(new DateValidator());
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

