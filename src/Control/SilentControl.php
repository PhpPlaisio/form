<?php
//----------------------------------------------------------------------------------------------------------------------
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
   */
  public function generate()
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
   */
  public function getHtmlTableCell()
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    // Get the submitted value and clean it (if required).
    if (isset($submittedValue[$submitName]))
    {
      if ($this->cleaner)
      {
        $newValue = $this->cleaner->clean($submittedValue[$submitName]);
      }
      else
      {
        $newValue = $submittedValue[$submitName];
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
