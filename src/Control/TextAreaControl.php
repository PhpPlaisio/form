<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\TrimWhitespaceCleaner;
use SetBased\Abc\Helper\Html;

/**
 * Class for form controls of type [textarea](http://www.w3schools.com/tags/tag_textarea.asp).
 */
class TextAreaControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    // By default whitespace is trimmed from textarea form controls.
    $this->cleaner = TrimWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function generate(): string
  {
    $this->attributes['name'] = $this->submitName;

    $html = $this->prefix;
    $html .= Html::generateElement('textarea', $this->attributes, (string)$this->value);
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [cols](http://www.w3schools.com/tags/att_textarea_cols.asp).
   *
   * @param int|null $value The attribute value.
   */
  public function setAttrCols(?int $value): void
  {
    $this->attributes['cols'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [rows](http://www.w3schools.com/tags/att_textarea_rows.asp).
   *
   * @param int|null $value The attribute value.
   */
  public function setAttrRows(?int $value): void
  {
    $this->attributes['rows'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [wrap](http://www.w3schools.com/tags/att_textarea_wrap.asp). Possible values:
   * * soft
   * * hard
   *
   * @param string|null $value The attribute value.
   */
  public function setAttrWrap(?string $value): void
  {
    $this->attributes['wrap'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(array $submittedValues,
                                             array &$whiteListValues,
                                             array &$changedInputs): void
  {
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    // Get the submitted value and cleaned (if required).
    if ($this->cleaner)
    {
      $newValue = $this->cleaner->clean($submittedValues[$submitName]);
    }
    else
    {
      $newValue = $submittedValues[$submitName];
    }

    if ((string)$this->value!==(string)$newValue)
    {
      $changedInputs[$this->name] = $this;
      $this->value                = $newValue;
    }

    $whiteListValues[$this->name] = $newValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
