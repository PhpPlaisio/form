<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Functional\CompoundCleaner;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\HiddenControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Test cases for compound cleaner and changed form controls.
 */
class CompoundCleanerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * test compound cleaner is called by LoadWalker.
   */
  public function testCleaner1(): void
  {
    $_POST = ['A/B' => 'a/b'];

    $form = $this->createForm();
    $form->execute();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame($values['A'], 'a');
    self::assertSame($values['B'], 'b');
    self::assertSame([], $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * test compound cleaner is called by LoadWalker.
   */
  public function testCleaner2(): void
  {
    $_POST = ['A/B' => 'a/c'];

    $form = $this->createForm();
    $form->execute();

    $values  = $form->getValues();
    $changed = $form->getChangedControls();

    self::assertSame('a', $values['A']);
    self::assertSame('c', $values['B']);
    self::assertArrayHasKey('B', $changed);
    self::assertArrayNotHasKey('A', $changed);
    self::assertArrayNotHasKey('A/B', $changed);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a from for testing compound cleaner.
   */
  private function createForm(): RawForm
  {
    $form = new RawForm();

    $fieldSet = new FieldSet();
    $fieldSet->addCleaner(new TestCompoundCleaner());

    $input = new HiddenControl('A');
    $fieldSet->addFormControl($input);

    $input = new HiddenControl('B');
    $fieldSet->addFormControl($input);

    $input = new HiddenControl('A/B');
    $fieldSet->addFormControl($input);

    $submit = new ForceSubmitControl('submit', true);
    $submit->setMethod('handleForm');
    $fieldSet->addFormControl($submit);

    $form->addFieldSet($fieldSet);
    $form->setValues(['A' => 'a', 'B' => 'b']);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
