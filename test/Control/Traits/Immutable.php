<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control\Traits;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Form\Test\TestForm;

/**
 * Test for immutable form controls.
 */
trait Immutable
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test values of immutable form control do not change.
   */
  public function testImmutable(): void
  {
    $_POST['immutable'] = 'Bye, bye!';

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('immutable');
    $input->setValue('Hello, World!');
    $input->setImmutable(true);
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEquals('Hello, World!', $values['immutable']);
    self::assertArrayNotHasKey('immutable', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for mutability setters and getters.
   */
  public function testIsMutable(): void
  {
    /** @var Mutability $input */
    $input = $this->getControl('mutable');
    self::assertFalse($input->isImmutable());
    self::assertTrue($input->isMutable());

    $input = $this->getControl('mutable');
    $input->setImmutable(true);
    self::assertTrue($input->isImmutable());
    self::assertFalse($input->isMutable());

    $input = $this->getControl('mutable');
    $input->setImmutable(false);
    self::assertFalse($input->isImmutable());
    self::assertTrue($input->isMutable());

    $input = $this->getControl('mutable');
    $input->setImmutable(null);
    self::assertFalse($input->isImmutable());
    self::assertTrue($input->isMutable());

    $input = $this->getControl('mutable');
    $input->setMutable(true);
    self::assertFalse($input->isImmutable());
    self::assertTrue($input->isMutable());

    $input = $this->getControl('mutable');
    $input->setMutable(false);
    self::assertTrue($input->isImmutable());
    self::assertFalse($input->isMutable());

    $input = $this->getControl('mutable');
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
    $_POST['mutable'] = 'Bye, bye!';

    $form     = new TestForm();
    $fieldset = new FieldSet();
    $form->addFieldSet($fieldset);

    $input = $this->getControl('mutable');
    $input->setValue('Hello, World!');
    $input->setMutable(true);
    $fieldset->addFormControl($input);

    $form->loadSubmittedValues();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertEquals('Bye, bye!', $values['mutable']);
    self::assertArrayHasKey('mutable', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
