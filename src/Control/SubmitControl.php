<?php

namespace SetBased\Abc\Form\Control;

/**
 * Class for form controls of type [input:submit](http://www.w3schools.com/tags/tag_input.asp).
 */
class SubmitControl extends PushMeControl
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

    $this->buttonType = 'submit';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formaction](http://www.w3schools.com/tags/att_input_formaction.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrFormAction(?string $value): void
  {
    $this->attributes['formaction'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formenctype](http://www.w3schools.com/tags/att_input_formenctype.asp). Possible values:
   * * application/x-www-form-urlencoded (default)
   * * multipart/form-data
   * * text/plain
   *
   * @param string|null $value The attribute value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrFormEncType(?string $value): void
  {
    $this->attributes['formenctype'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formmethod](http://www.w3schools.com/tags/att_input_formmethod.asp). Possible values:
   * * post (default)
   * * get
   *
   * @param string|null $value The attribute value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrFormMethod(?string $value): void
  {
    $this->attributes['formmethod'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formtarget](http://www.w3schools.com/tags/att_input_formtarget.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrFormTarget(?string $value): void
  {
    $this->attributes['formtarget'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
