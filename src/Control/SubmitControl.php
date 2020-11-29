<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

/**
 * Class for form controls of type [input:submit](http://www.w3schools.com/tags/tag_input.asp).
 */
class SubmitControl extends PushControl
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
   * Returns true.
   *
   * @since 1.0.0
   * @api
   */
  public function isSubmitTrigger(): bool
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formaction](http://www.w3schools.com/tags/att_input_formaction.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrFormAction(?string $value): self
  {
    $this->attributes['formaction'] = $value;

    return $this;
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
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrFormEncType(?string $value): self
  {
    $this->attributes['formenctype'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formmethod](http://www.w3schools.com/tags/att_input_formmethod.asp). Possible values:
   * * post (default)
   * * get
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrFormMethod(?string $value): self
  {
    $this->attributes['formmethod'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [formtarget](http://www.w3schools.com/tags/att_input_formtarget.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrFormTarget(?string $value): self
  {
    $this->attributes['formtarget'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
