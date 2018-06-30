<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\PasswordControl;

/**
 * Unit tests for class PasswordControl.
 */
class PasswordControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden()
  {
    $control = new PasswordControl('hidden');

    self::assertSame(false, $control->isHidden());
  }
  //--------------------------------------------------------------------------------------------------------------------
  protected function getControl($name)
  {
    return new PasswordControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
