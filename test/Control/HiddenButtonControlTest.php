<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\HiddenButtonControl;

/**
 * Unit tests for class HiddenButtonControl.
 */
class HiddenButtonControlTest extends PushControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new HiddenButtonControl('hidden-button');

    self::assertSame(true, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl($name)
  {
    return new HiddenButtonControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns input type for form control.
   *
   * @return string
   */
  protected function getControlType()
  {
    return 'hidden';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
