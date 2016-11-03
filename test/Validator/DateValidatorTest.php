<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Form\Control\DateControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\RawForm;
use SetBased\Abc\Form\Validator\DateValidator;

//----------------------------------------------------------------------------------------------------------------------
class DateValidatorTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against invalid dates.
   */
  public function testInvalidDates()
  {
    $dates = ['15- 7 -20', '10-april-1966', 'Hello world.', '15- 7 -20', '2015-02-29'];

    foreach ($dates as $date)
    {
      $_POST['date'] = $date;
      $form          = $this->setupForm1();

      $this->assertFalse($form->validate(), "Submitted: $date");
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test against valid dates.
   */
  public function testValidDates()
  {
    $dates = ['1966-04-10', '1970-01-01', '1900-01-01', '9999-12-31', '2020-02-29'];

    foreach ($dates as $date)
    {
      $_POST['date'] = $date;
      $form          = $this->setupForm1();

      $this->assertTrue($form->validate(), "Submitted: $date");
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Setups a form with a text form control (which must be a valid email address) values.
   */
  private function setupForm1()
  {
    $form     = new RawForm();
    $fieldset = new FieldSet('');
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

