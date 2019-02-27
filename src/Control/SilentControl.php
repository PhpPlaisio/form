<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Cast;
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
    $submitKey = $this->submitKey();

    // Get the submitted value.
    $newValue = $submittedValues[$submitKey] ?? null;

    // Clean the submitted value, if we have a cleaner.
    if ($this->cleaner) $newValue = $this->cleaner->clean($newValue);

    if (Cast::toManString($this->value, '')!==Cast::toManString($newValue, ''))
    {
      $this->value = $newValue;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
