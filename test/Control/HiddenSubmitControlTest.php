<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\HiddenSubmitControl;

/**
 * Unit tests for class HiddenSubmitControl.
 */
class HiddenSubmitControlTest extends PushControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new HiddenSubmitControl('hidden-button');

    self::assertSame(true, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl($name)
  {
    return new HiddenSubmitControl($name);
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
