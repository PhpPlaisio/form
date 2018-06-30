<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\ButtonControl;

/**
 * Unit tests for class ButtonControl.
 */
class ButtonControlTest extends PushMeControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new ButtonControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl($name)
  {
    return new ButtonControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns button type for form control.
   *
   * @return string
   */
  protected function getControlType()
  {
    return 'button';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
