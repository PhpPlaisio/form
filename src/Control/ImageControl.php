<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;
use SetBased\Exception\LogicException;

/**
 * Class for form controls of type [input:image](http://www.w3schools.com/tags/tag_input.asp).
 */
class ImageControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $this->attributes['type'] = 'image';
    $this->attributes['name'] = $this->submitName;

    $ret = $this->prefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [alt](http://www.w3schools.com/tags/att_input_alt.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAlt(?string $value): void
  {
    $this->attributes['alt'] = $value;
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
  /**
   * Sets the attribute [height](http://www.w3schools.com/tags/att_input_height.asp).
   *
   * @param int|null $value The attribute value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrHeight(?int $value): void
  {
    $this->attributes['height'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [src](http://www.w3schools.com/tags/att_input_src.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrSrc(?string $value): void
  {
    $this->attributes['src'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [width](http://www.w3schools.com/tags/att_input_width.asp).
   *
   * @param int|null $value The attribute value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrWidth(?int $value): void
  {
    $this->attributes['width'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Does nothing. It is not possible the set the value of an image form control.
   *
   * @param mixed $value Not used.
   *
   * @since 1.0.0
   * @api
   */
  public function setValue($value): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(array $submittedValues,
                                             array &$whiteListValues,
                                             array &$changedInputs): void
  {
    throw new LogicException('Not implemented.');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
