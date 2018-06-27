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
  public function generate(): string
  {
    $this->attributes['type']    = 'checkbox';
    $this->attributes['name']    = $this->submitName;
    $this->attributes['checked'] = !empty($this->value);

    $html = $this->prefix;
    $html .= $this->generatePrefixLabel();
    $html .= Html::generateVoidElement('input', $this->attributes);
    $html .= $this->generatePostfixLabel();
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlTableCell(): string
  {
    return '<td class="control checkbox">'.$this->generate().'</td>';
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

    if (empty($this->value)!==empty($submittedValues[$submitName]))
    {
      $changedInputs[$this->name] = $this;
    }

    if (!empty($submittedValues[$submitName]))
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
