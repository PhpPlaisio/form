<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * Class for form controls of type [input:checkbox](http://www.w3schools.com/tags/tag_input.asp).
 */
class CheckboxControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The value of this form control.
   *
   * @var bool
   */
  protected $value;

  /**
   * The value that must be used when the submitted value is checked.
   *
   * @var mixed
   */
  protected $valueChecked = true;

  /**
   * The value that must be used when the submitted value is not checked.
   *
   * @var mixed
   */
  protected $valueUnchecked = false;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  public function generate()
  {
    $this->attributes['type']    = 'checkbox';
    $this->attributes['name']    = $this->submitName;
    $this->attributes['checked'] = $this->value;

    $html = $this->prefix;
    $html .= $this->generatePrefixLabel();
    $html .= Html::generateVoidElement('input', $this->attributes);
    $html .= $this->generatePostfixLabel();
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control in a table cell.
   *
   * @return string
   */
  public function getHtmlTableCell()
  {
    return '<td class="control checkbox">'.$this->generate().'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values that must be used when the submitted value is checked and not checked.
   *
   * By default values true (checked) and false (not checked) are used.
   *
   * If the value of this checkbox is stored as not nullable integer in a database one might use 1 for checked and 0
   * for not checked.
   *
   * @param mixed $checked   The value that must be used when the submitted value is checked.
   * @param mixed $unchecked The value that must be used when the submitted value is not checked.
   */
  public function useValues($checked, $unchecked)
  {
    $this->valueChecked   = $checked;
    $this->valueUnchecked = $unchecked;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    if (empty($this->value)!==empty($submittedValue[$submitName]))
    {
      $changedInputs[$this->name] = $this;
    }

    if (!empty($submittedValue[$submitName]))
    {
      $this->value                 = $this->valueChecked;
      $whiteListValue[$this->name] = $this->valueChecked;
    }
    else
    {
      $this->value                 = $this->valueUnchecked;
      $whiteListValue[$this->name] = $this->valueUnchecked;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
