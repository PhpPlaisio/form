<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control\Traits;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Form\RawForm;

/**
 * Test for immutable form controls.
 */
trait ImmutableTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values of immutable form control do not change.
   */
  public function testImmutable(): void
  {
    $_POST['immutable'] = $this->getValidSubmittedValue();

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->createControl('immutable');
    $input->setValue($this->getValidInitialValue())
          ->setImmutable(true);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);
    self::assertSame($this->getValidInitialValue(), $values['immutable']);
    self::assertArrayNotHasKey('immutable', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for mutability setters and getters.
   */
  public function testIsMutable(): void
  {
    /** @var Mutability $input */
    $input = $this->createControl('mutable');
    self::assertFalse($input->isImmutable());
    self::assertTrue($input->isMutable());

    $input = $this->createControl('mutable');
    $input->setImmutable(true);
    self::assertTrue($input->isImmutable());
    self::assertFalse($input->isMutable());

    $input = $this->createControl('mutable');
    $input->setImmutable(false);
    self::assertFalse($input->isImmutable());
    self::assertTrue($input->isMutable());

    $input = $this->createControl('mutable');
    $input->setImmutable(null);
    self::assertFalse($input->isImmutable());
    self::assertTrue($input->isMutable());

    $input = $this->createControl('mutable');
    $input->setMutable(true);
    self::assertFalse($input->isImmutable());
    self::assertTrue($input->isMutable());

    $input = $this->createControl('mutable');
    $input->setMutable(false);
    self::assertTrue($input->isImmutable());
    self::assertFalse($input->isMutable());

    $input = $this->createControl('mutable');
    $input->setMutable(null);
    self::assertFalse($input->isImmutable());
    self::assertTrue($input->isMutable());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values of mutable form control do change.
   */
  public function testMutable(): void
  {
    $_POST['mutable'] = $this->getValidSubmittedValue();

    $form     = new RawForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->createControl('mutable');
    $input->setValue($this->getValidInitialValue())
          ->setMutable(true);
    $fieldset->addFormControl($input);

    $input = new ForceSubmitControl('submit', true);
    $input->setMethod('handleSubmit');
    $fieldset->addFormControl($input);

    $method  = $form->execute();
    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('handleSubmit', $method);
    self::assertSame($this->getValidSubmittedValue(), $values['mutable']);
    self::assertArrayHasKey('mutable', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
