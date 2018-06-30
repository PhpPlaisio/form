<?php

namespace SetBased\Abc\Form\Test\Control;

use SetBased\Abc\Form\Control\PasswordControl;

class PasswordControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  protected function getControl($name)
  {
    return new PasswordControl($name);
  }
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
}

//----------------------------------------------------------------------------------------------------------------------
