<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Cleaner\DateCleaner;
use Plaisio\Form\Control\DateControl;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Formatter\DateFormatter;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\ImmutableTest;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Control\Traits\InputElementTest2;

/**
 * Unit tests for class DateControl.
 */
class DateControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  use ImmutableTest;
  use InputElementTest1;
  use InputElementTest2;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cleaning and formatting is done before testing value of the form control has changed.
   * For text field whitespaceOnly cleaner set default.
   */
  public function testFormattingAndCleaning(): void
  {
    $_POST['birthday'] = '10.04.1966';

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = new DateControl('birthday');
    $input->setValue('1966-04-10')
          ->addCleaner(new DateCleaner('d-m-Y', '-', '/-. '));
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);

    // After formatting and clean the date must be in ISO 8601 format.
    self::assertEquals('1966-04-10', $values['birthday']);

    // Effectively the date is not changed.
    self::assertArrayNotHasKey('birthday', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test open date. Empty value yields open date and no changed form controls.
   */
  public function testOpenDate1(): void
  {
    $openDateStart = '1900-01-01';
    $openDateEnd   = '9999-12-31';

    $_POST['date_start'] = null;
    $_POST['date_end']   = null;

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $this->openDateTest($openDateStart, $openDateEnd);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test open date. Open dates value yields open date and changed form controls.
   */
  public function testOpenDate2(): void
  {
    $openDateStart = '1900-01-01';
    $openDateEnd   = '9999-12-31';

    $_POST['date_start'] = $openDateStart;
    $_POST['date_end']   = $openDateEnd;

    $this->openDateTest($openDateStart, $openDateEnd);
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(string $name): SimpleControl
  {
    return new DateControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'date';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid initial value.
   *
   * @return mixed
   */
  protected function getValidInitialValue()
  {
    return '2000-01-01';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid submitted value (different form initial value).
   *
   * @return string
   */
  protected function getValidSubmittedValue(): string
  {
    return '1999-12-31';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param string $openDateStart
   * @param string $openDateEnd
   */
  private function openDateTest(string $openDateStart, string $openDateEnd): void
  {
    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    // First set open date and then add cleaner.
    $input = new DateControl('date_start');
    $input->setValue($openDateStart)
          ->setOpenDate($openDateStart)
          ->setFormatter(new DateFormatter())
          ->addCleaner(new DateCleaner());
    $fieldset->addFormControl($input);

    // Change order of setting open data and adding cleaner and formatter.
    $input = new DateControl('date_end');
    $input->setValue($openDateEnd)
          ->addCleaner(new DateCleaner())
          ->setFormatter(new DateFormatter())
          ->setOpenDate($openDateEnd);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();
    $html    = $form->getHtml();

    self::assertSame('handleSubmit', $method);
    self::assertEquals($openDateStart, $values['date_start']);
    self::assertEquals($openDateEnd, $values['date_end']);
    self::assertArrayNotHasKey('date_start', $changed);
    self::assertArrayNotHasKey('date_end', $changed);
    // Open dates are empty values in form controls.
    self::assertStringContainsString('<input class="frm-date" type="date" name="date_start"/', $html);
    self::assertStringContainsString('<input class="frm-date" type="date" name="date_end"/', $html);
  }
}

//----------------------------------------------------------------------------------------------------------------------
