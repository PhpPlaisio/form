<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Functional\Dependant;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\RawForm;

/**
 * Test dependant controls.
 */
class DependantTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid submitted values.
   */
  public function testInvalid(): void
  {
    $_POST = ['country' => ['abbreviation' => 'USA'],
              'state'   => ['abbreviation' => 'ZH']]; // ZH is not a whitelisted value.

    $form = $this->createForm();
    $form->execute();
    $values = $form->getValues();
    unset($values['state']['submit']);

    self::assertSame(['country' => ['abbreviation' => 'USA'],
                      'state'   => ['abbreviation' => null]],
                     $values);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid submitted values.
   */
  public function testValid1(): void
  {
    $_POST = ['country' => ['abbreviation' => 'NED'],
              'state'   => ['abbreviation' => 'ZH']];

    $form = $this->createForm();
    $form->execute();
    $values = $form->getValues();
    unset($values['state']['submit']);

    self::assertSame(['country' => ['abbreviation' => 'NED'],
                      'state'   => ['abbreviation' => 'ZH']],
                     $values);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with valid submitted values.
   */
  public function testValid2(): void
  {
    $_POST = ['country' => ['abbreviation' => 'USA'],
              'state'   => ['abbreviation' => 'NY']];

    $form = $this->createForm();
    $form->execute();
    $values = $form->getValues();
    unset($values['state']['submit']);

    self::assertSame(['country' => ['abbreviation' => 'USA'],
                      'state'   => ['abbreviation' => 'NY']],
                     $values);
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function createForm(): RawForm
  {
    $form = new RawForm();

    $fieldSet = new FieldSet('country');

    $countries = [['key' => 'USA', 'name' => 'USA'],
                  ['key' => 'NED', 'name' => 'Netherlands']];
    $input     = new SelectControl('abbreviation');
    $input->setOptions($countries, 'key', 'name');

    $fieldSet->addFormControl($input);
    $form->addFieldSet($fieldSet);

    $fieldSet = new FieldSet('state');

    $input = new StateControl('abbreviation');
    $input->setOptions([], 'key', 'name');

    $submit = new ForceSubmitControl('submit', true);
    $submit->setMethod('handleForm');
    $fieldSet->addFormControl($submit);

    $fieldSet->addFormControl($input);
    $form->addFieldSet($fieldSet);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
