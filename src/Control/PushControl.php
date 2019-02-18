<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;
use SetBased\Exception\LogicException;

/**
 * Parent class for form controls submit, reset, and button.
 */
class PushControl extends SimpleControl
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

  /**
   * The name of the method for handling the form when the form submit is triggered by this control.
   *
   * @var string|null
   */
  protected $method;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $this->attributes['type'] = $this->buttonType;
    $this->attributes['name'] = $this->submitName;

    if ($this->formatter) $this->attributes['value'] = $this->formatter->format($this->value);
    else                  $this->attributes['value'] = $this->value;

    $ret = $this->prefix;
    $ret .= $this->getHtmlPrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->getHtmlPostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array $values Not used.
   */
  public function mergeValuesBase(array $values): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the name of the method for handling the form when the form submit is triggered by this control.
   *
   * @param null|string $method The name of the method.
   */
  public function setMethod(?string $method): void
  {
    $this->method = $method;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array $values Not used.
   */
  public function setValuesBase(?array $values): void
  {
    // Nothing to do.
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

    if (isset($submittedValues[$submitKey]) && (string)$submittedValues[$submitKey]===(string)$this->value)
    {
      // We don't register buttons as a changed input, otherwise every submitted form will always have changed inputs.
      // So, skip the following code.
      // $changedInputs[$this->myName] = $this;

      $whiteListValues[$this->name] = $this->value;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function searchSubmitHandler(array $submittedValues): ?string
  {
    $submitKey = $this->submitKey();

    if (isset($submittedValues[$submitKey]) && (string)$submittedValues[$submitKey]===(string)$this->value)
    {
      if ($this->method===null)
      {
        throw new LogicException('Submit handler method not set');
      }

      return $this->method;
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
