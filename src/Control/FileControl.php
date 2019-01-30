<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * Class for form controls of type [input:file](http://www.w3schools.com/tags/tag_input.asp) that allows a single file
 * to be uploaded.
 */
class FileControl extends SimpleControl
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
    $this->attributes['type'] = 'file';
    $this->attributes['name'] = $this->submitName;

    $ret = $this->prefix;
    $ret .= $this->getHtmlPrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->getHtmlPostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [accept](http://www.w3schools.com/tags/att_input_accept.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAccept(?string $value): void
  {
    $this->attributes['accept'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Does nothing. It is not possible the set the value of a file form control.
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
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode((int)$this->name) : $this->name;

    if (isset($_FILES[$submitName]['error']) && $_FILES[$submitName]['error']===0)
    {
      $changedInputs[$this->name]   = $this;
      $whiteListValues[$this->name] = $_FILES[$submitName];
      $this->value                  = $_FILES[$submitName];
    }
    else
    {
      $this->value                  = null;
      $whiteListValues[$this->name] = null;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
