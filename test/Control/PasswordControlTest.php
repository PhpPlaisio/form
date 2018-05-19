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
}

//----------------------------------------------------------------------------------------------------------------------
