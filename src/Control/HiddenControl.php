<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * Class for form controls of type [input:hidden](http://www.w3schools.com/tags/tag_input.asp).
 */
class HiddenControl extends SimpleControl
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
    $this->attributes['type'] = 'hidden';
    $this->attributes['name'] = $this->submitName;

    if ($this->formatter) $this->attributes['value'] = $this->formatter->format($this->value);
    else                  $this->attributes['value'] = $this->value;

    $ret = $this->prefix;
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A hidden control must never be shown in a table.
   *
   * @return string An empty string.
   */
  public function getHtmlTableCell(): string
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true.
   *
   * @since 1.0.0
   * @api
   */
  public function isHidden(): bool
  {
    return true;
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

    // Get the submitted value.
    $newValue = $submittedValues[$submitName] ?? null;

    // Clean the submitted value, if we have a cleaner.
    if ($this->cleaner) $newValue = $this->cleaner->clean($newValue);

    if ((string)$this->value!==(string)$newValue)
    {
      $changedInputs[$this->name] = $this;
      $this->value                = $newValue;
    }

    // Any text can be in a input:hidden box. So, any value is white listed.
    $whiteListValues[$this->name] = $newValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
