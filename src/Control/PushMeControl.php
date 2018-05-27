<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * parent class for form controls submit, reset, and button.
 */
class PushMeControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /** The type of this button. Valid values are:
   *  <ul>
   *  <li> submit
   *  <li> reset
   *  <li> button
   *  </ul>
   */
  protected $buttonType;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function generate()
  {
    $this->attributes['type'] = $this->buttonType;
    $this->attributes['name'] = $this->submitName;

    if ($this->formatter) $this->attributes['value'] = $this->formatter->format($this->value);
    else                  $this->attributes['value'] = $this->value;

    $ret = $this->prefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not retrieved by this method.
   *
   * @param array $values Not used.
   */
  public function getCurrentValues(&$values)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array $values Not used.
   */
  public function mergeValuesBase($values)
  {
    // Nothing to do.
    unset($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array $values Not used.
   */
  public function setValuesBase($values)
  {
    // Nothing to do.
    unset($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase($submittedValues, &$whiteListValues, &$changedInputs)
  {
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    if (isset($submittedValues[$submitName]) && $submittedValues[$submitName]===$this->value)
    {
      // We don't register buttons as a changed input, otherwise every submitted form will always have changed inputs.
      // So, skip the following code.
      // $changedInputs[$this->myName] = $this;

      $whiteListValues[$this->name] = $this->value;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
