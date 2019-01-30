<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * Class for form controls of type [input:hidden](http://www.w3schools.com/tags/tag_input.asp), however
 */
class SilentControl extends SimpleControl
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
   * A silent control must never be shown in a table.
   *
   * @return string An empty string.
   *
   * @since 1.0.0
   * @api
   */
  public function getHtmlTableCell(): string
  {
    return '';
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

    // Get the submitted value and clean it (if required).
    if (isset($submittedValues[$submitName]))
    {
      if ($this->cleaner)
      {
        $newValue = $this->cleaner->clean($submittedValues[$submitName]);
      }
      else
      {
        $newValue = $submittedValues[$submitName];
      }
    }
    else
    {
      $newValue = null;
    }

    // Normalize old (original) value and new (submitted) value.
    $oldValue = (string)$this->value;
    $newValue = (string)$newValue;

    if ($oldValue!==$newValue)
    {
      $this->value = $newValue;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
