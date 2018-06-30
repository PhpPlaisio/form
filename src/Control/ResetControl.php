<?php

namespace SetBased\Abc\Form\Control;

/**
 * Class for form controls of type [input:reset](http://www.w3schools.com/tags/tag_input.asp).
 */
class ResetControl extends PushControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    $this->buttonType = 'reset';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
