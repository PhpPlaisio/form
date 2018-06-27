<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Obfuscator\Obfuscator;

/**
 * Class RadiosControl
 *
 * @package SetBased\Form\Form\Control
 */
class RadiosControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The key in $options holding the disabled flag for the radio buttons.
   *
   * @var string
   */
  protected $disabledKey;

  /**
   * The key in $options holding the HTML ID attribute of the radios.
   *
   * @var string|null
   */
  protected $idKey;

  /**
   * The key in $options holding the keys for the radio buttons.
   *
   * @var string
   */
  protected $keyKey;

  /**
   * If true and only if true labels are HTML code.
   *
   * @var bool
   */
  protected $labelIsHtml = false;

  /**
   * The key in $options holding the labels for the radio buttons.
   *
   * @var string
   */
  protected $labelKey;

  /**
   * The HTML snippet appended after each label for the radio buttons.
   *
   * @var string
   */
  protected $labelPostfix = '';

  /**
   * The HTML snippet inserted before each label for the radio buttons.
   *
   * @var string
   */
  protected $labelPrefix = '';

  /**
   * The data for the radio buttons.
   *
   * @var array[]
   */
  protected $options;

  /**
   * The obfuscator for the names of the radio buttons.
   *
   * @var Obfuscator
   */
  protected $optionsObfuscator;

  /**
   * The value of the checked radio button.
   *
   * @var string
   */
  protected $value;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function generate(): string
  {
    $html = $this->prefix;
    $html .= Html::generateTag('span', $this->attributes);

    if (is_array($this->options))
    {
      $inputAttributes = ['type'     => 'radio',
                          'name'     => $this->submitName,
                          'id'       => '',
                          'value'    => '',
                          'checked'  => false,
                          'disabled' => false];
      // Note: we use a reference to unsure that the for attribute of the label and the id attribute of the radio
      // button match.
      $labelAttributes = ['for' => &$inputAttributes['id']];

      foreach ($this->options as $option)
      {
        $key   = (string)$option[$this->keyKey];
        $value = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode($key) : $key;

        $inputAttributes['id']       = (isset($this->idKey) && isset($option[$this->idKey])) ? $option[$this->idKey] : Html::getAutoId();
        $inputAttributes['value']    = $value;
        $inputAttributes['checked']  = ((string)$this->value===(string)$key);
        $inputAttributes['disabled'] = ($this->disabledKey && !empty($option[$this->disabledKey]));

        $html .= Html::generateVoidElement('input', $inputAttributes);

        $html .= $this->labelPrefix;
        $html .= Html::generateElement('label', $labelAttributes, $option[$this->labelKey], $this->labelIsHtml);
        $html .= $this->labelPostfix;
      }
    }

    $html .= '</span>';
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds the value of a check radio button the values with the name of this form control as key.
   *
   * @param array $values The values.
   */
  public function getSetValuesBase(array &$values): void
  {
    $values[$this->name] = $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of the check radio button.
   *
   * @returns string
   *
   * @since 1.0.0
   * @api
   */
  public function getSubmittedValue()
  {
    return $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of the checked radio button.
   *
   * @param array $values
   */
  public function mergeValuesBase(array $values): void
  {
    if (array_key_exists($this->name, $values))
    {
      $this->setValuesBase($values);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets whether labels are HTML code.
   *
   * @param bool $labelIsHtml If true and only if true labels are HTML code.
   *
   * @since 1.0.0
   * @api
   */
  public function setLabelIsHtml(bool $labelIsHtml = true): void
  {
    $this->labelIsHtml = $labelIsHtml;
  }

//--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label postfix., e.g. the HTML code that is appended after the HTML code of each label of the checkboxes.
   *
   * @param string|null $htmlSnippet The label postfix.
   *
   * @since 1.0.0
   * @api
   */
  public function setLabelPostfix(?string $htmlSnippet): void
  {
    $this->labelPostfix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label prefix, e.g. the HTML code that is inserted before the HTML code of each label of the checkboxes.
   *
   * @param string|null $htmlSnippet The label prefix.
   */
  public function setLabelPrefix(?string $htmlSnippet): void
  {
    $this->labelPrefix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for this select box.
   *
   * @param array[]     $options     An array of arrays with the options.
   * @param string      $keyKey      The key holding the keys of the radio buttons.
   * @param string      $labelKey    The key holding the labels for the radio buttons..
   * @param string|null $disabledKey The key holding the disabled flag. Any
   *                                 [non-empty](http://php.net/manual/function.empty.php) value results that the radio
   *                                 button is disabled.
   * @param string|null $idKey       The key holding the HTML ID attribute of the radios.
   *
   * @since 1.0.0
   * @api
   */
  public function setOptions(array &$options,
                             string $keyKey,
                             string $labelKey,
                             ?string $disabledKey = null,
                             ?string $idKey = null)
  {
    $this->options     = $options;
    $this->keyKey      = $keyKey;
    $this->labelKey    = $labelKey;
    $this->disabledKey = $disabledKey;
    $this->idKey       = $idKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the values of the radio buttons. This method should be used when the values of the radio
   * buttons are database IDs.
   *
   * @param Obfuscator|null $obfuscator The obfuscator for the radio buttons values.
   *
   * @since 1.0.0
   * @api
   */
  public function setOptionsObfuscator(?Obfuscator $obfuscator): void
  {
    $this->optionsObfuscator = $obfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param string $value The new value for the form control.
   *
   * @since 1.0.0
   * @api
   */
  public function setValue(string $value): void
  {
    $this->value = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function setValuesBase(?array $values): void
  {
    $this->value = $values[$this->name] ?? null;
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

    if (isset($submittedValues[$submitName]))
    {
      // Normalize the submitted value as a string.
      $newValue = (string)$submittedValues[$submitName];

      foreach ($this->options as $option)
      {
        // Get the (database) ID of the option.
        $key = (string)$option[$this->keyKey];

        // If an obfuscator is installed compute the obfuscated code of the radio button name.
        $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode($key) : $key;

        if ($newValue===(string)$code)
        {
          // If the original value differs from the submitted value then the form control has been changed.
          if ((string)$this->value!==$key)
          {
            $changedInputs[$this->name] = $this;
          }

          // Set the white listed value.
          $whiteListValues[$this->name] = $key;
          $this->value                  = $key;

          // Leave the loop after first match.
          break;
        }
      }
    }
    else
    {
      // No radio button has been checked.
      $whiteListValues[$this->name] = null;
      $this->value                  = null;
    }

    if (!array_key_exists($this->name, $whiteListValues))
    {
      // The white listed value has not been set. This can only happen when a none white listed value has been submitted.
      // In this case we ignore this and assume the default value has been submitted.
      $whiteListValues[$this->name] = $this->value;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function validateBase(array &$invalidFormControls): bool
  {
    $valid = true;

    foreach ($this->validators as $validator)
    {
      $valid = $validator->validate($this);
      if ($valid!==true)
      {
        $invalidFormControls[] = $this;
        break;
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
