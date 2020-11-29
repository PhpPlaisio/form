<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Helper\Html;
use SetBased\Helper\Cast;

/**
 * Class for form controls of type [input:radio](http://www.w3schools.com/tags/tag_input.asp).
 */
class RadioControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use Mutability;

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
    $ret = $this->prefix;
    $ret .= $this->getHtmlPrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->getHtmlPostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [value](http://www.w3schools.com/tags/att_input_value.asp).
   *
   * @param mixed $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrValue($value): self
  {
    $this->attributes['value'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(array $submittedValues,
                                             array &$whiteListValues,
                                             array &$changedInputs): void
  {
    if ($this->immutable===true)
    {
      if (!isset($whiteListValues[$this->name]) && !empty($this->attributes['checked']))
      {
        $whiteListValues[$this->name] = $this->attributes['value'];
      }
    }
    else
    {
      $submitKey = $this->submitKey();
      $newValue  = $submittedValues[$submitKey] ?? '';

      if (isset($this->attributes['value']) &&
        Cast::toManString($newValue, '')===Cast::toManString($this->attributes['value'], ''))
      {
        if (empty($this->attributes['checked']))
        {
          $changedInputs[$this->name] = $this;
        }
        $this->attributes['checked']  = true;
        $whiteListValues[$this->name] = $this->attributes['value'];
        $this->value                  = $this->attributes['value'];
      }
      else
      {
        if (!empty($this->attributes['checked']))
        {
          $changedInputs[$this->name] = $this;
        }
        $this->attributes['checked'] = false;
        $this->value                 = null;

        // If the white listed value is not set by a radio button with the same name as this radio button, set the white
        // listed value of this radio button (and other radio buttons with the same name) to null. If another radio button
        // with the same name is checked the white listed value will be overwritten.
        if (!isset($whiteListValues[$this->name]))
        {
          $whiteListValues[$this->name] = null;
        }
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form control for HTML code generation or loading submitted values.
   *
   * @param string $parentSubmitName The submit name of the parent control.
   *
   * @since 1.0.0
   * @api
   */
  protected function prepare(string $parentSubmitName): void
  {
    parent::prepare($parentSubmitName);

    $this->attributes['type'] = 'radio';
    $this->attributes['name'] = $this->submitName;

    // A radio button is checked if its value (not to be confused with attribute value) is not empty.
    if (!empty($this->value))
    {
      $this->attributes['checked'] = true;
    }
    else
    {
      unset($this->attributes['checked']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
