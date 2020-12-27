<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\NumberControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Test\Control\Traits\ImmutableTest;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Control\Traits\InputElementTest2;

/**
 * Unit tests for class NumberControl.
 */
class NumberControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  use ImmutableTest;
  use InputElementTest1;
  use InputElementTest2;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(string $name): SimpleControl
  {
    return new NumberControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'number';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid initial value.
   *
   * @return mixed
   */
  protected function getValidInitialValue()
  {
    return 123;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a valid submitted value (different form initial value).
   *
   * @return string
   */
  protected function getValidSubmittedValue(): string
  {
    return '456';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
