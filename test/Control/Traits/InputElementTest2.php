<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control\Traits;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\RawForm;

/**
 * Tests for input elements.
 */
trait InputElementTest2
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test empty values are converted to null.
   */
  public function testEmptyValueYieldsNull(): void
  {
    $_POST['myInput'] = '';

    $input = $this->createControl('myInput');

    $fieldSet = new FieldSet();
    $fieldSet->addFormControl($input);

    $input = new ForceSubmitControl('force', true);
    $input->setMethod('handleSubmit');
    $fieldSet->addFormControl($input);

    $form = new RawForm();
    $form->addFieldSet($fieldSet);

    $method = $form->execute();
    $values = $form->getValues();

    self::assertSame('handleSubmit', $method);
    self::assertNull($values['myInput']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
