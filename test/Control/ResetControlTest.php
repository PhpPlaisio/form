<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\ResetControl;

/**
 * Unit tests for class ResetControl.
 */
class ResetControlTest extends PushControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl($name)
  {
    return new ResetControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return reset type for form control.
   *
   * @return string
   */
  protected function getControlType()
  {
    return 'reset';
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new ResetControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
