<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * Class for form controls of type [input:checkbox](http://www.w3schools.com/tags/tag_input.asp).
 */
class CheckboxControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $this->attributes['type']    = 'checkbox';
    $this->attributes['name']    = $this->submitName;
    $this->attributes['checked'] = !empty($this->value);

    $html = $this->prefix;
    $html .= $this->getHtmlPrefixLabel();
    $html .= Html::generateVoidElement('input', $this->attributes);
    $html .= $this->getHtmlPostfixLabel();
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlTableCell(): string
  {
    return '<td class="control checkbox">'.$this->getHtml().'</td>';
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

    if (empty($this->value)!==empty($submittedValues[$submitKey]))
    {
      $changedInputs[$this->name] = $this;
    }

    if (!empty($submittedValues[$submitKey]))
    {
      $this->value                  = true;
      $whiteListValues[$this->name] = true;
    }
    else
    {
      $this->value                  = false;
      $whiteListValues[$this->name] = false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
