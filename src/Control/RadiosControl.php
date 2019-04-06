<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Obfuscator\Obfuscator;

/**
 * Class for an array of form controls of type [input:radio](http://www.w3schools.com/tags/tag_input.asp) with the same
 * name.
 */
class RadiosControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The map from the keys in the options to attribute names of the input elements.
   *
   * @var array|null
   */
  protected $inputAttributesMap;

  /**
   * The key in $options holding the keys for the radio buttons.
   *
   * @var string
   */
  protected $keyKey;

  /**
   * The map from the keys in the options to attribute names of the label elements.
   *
   * @var array|null
   */
  protected $labelAttributesMap;

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
   * @var array[]|null
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
   * @var string|null
   */
  protected $value;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    $this->attributes['class'] = 'radios';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $html = $this->prefix;
    $html .= Html::generateTag('span', $this->attributes);

    if (is_array($this->options))
    {
      // Normalize current value as a string.
      $valueAsString = Cast::toManString($this->value, '');

      foreach ($this->options as $option)
      {
        $inputAttributes = $this->inputAttributes($option);
        $labelAttributes = $this->labelAttributes($option);

        $labelAttributes['for'] = $inputAttributes['id'];

        $key         = $option[$this->keyKey];
        $keyAsString = Cast::toManString($key, '');
        $value       = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode(Cast::toOptInt($key)) : $key;

        $inputAttributes['value']   = $value;
        $inputAttributes['checked'] = ($valueAsString===$keyAsString);

        $html .= Html::generateVoidElement('input', $inputAttributes);

        $html .= $this->labelPrefix;
        $html .= Html::generateElement('label',
                                       $labelAttributes,
                                       Cast::toOptString($option[$this->labelKey]),
                                       $this->labelIsHtml);
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
   * Sets the map from the keys in the options to attribute names of the input element.
   *
   * Note the following attributes will ignored:
   * <ul>
   * <li> type
   * <li> name
   * <li> value
   * <li> checked
   * </ul>
   *
   * @param array|null $map The map.
   */
  public function setInputAttributesMap(?array $map)
  {
    $this->inputAttributesMap = $map;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the map from the keys in the options to attribute names of the label element.
   *
   * Note the following attribute will ignored:
   * <ul>
   * <li> for
   * </ul>
   *
   * @param array|null $map The map.
   */
  public function setLabelAttributesMap(?array $map)
  {
    $this->labelAttributesMap = $map;
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
   * @param array[]|null $options  An array of arrays with the options.
   * @param string       $keyKey   The key holding the keys of the radio buttons.
   * @param string       $labelKey The key holding the labels for the radio buttons.
   *
   * @since 1.0.0
   * @api
   */
  public function setOptions(?array &$options, string $keyKey, string $labelKey): void
  {
    $this->options  = $options;
    $this->keyKey   = $keyKey;
    $this->labelKey = $labelKey;
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
   * @param mixed $value The new value for the form control.
   *
   * @since 1.0.0
   * @api
   */
  public function setValue($value): void
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
    $submitKey = $this->submitKey();

    // Normalize current value as a string.
    $valueAsString = Cast::toManString($this->value, '');

    if (isset($submittedValues[$submitKey]))
    {
      // Normalize the submitted value as a string.
      $newValueAsString = Cast::toManString($submittedValues[$submitKey], '');

      foreach ($this->options as $option)
      {
        // Get the (database) ID of the option.
        $key         = $option[$this->keyKey];
        $keyAsString = Cast::toManString($key, '');

        // If an obfuscator is installed compute the obfuscated code of the radio button name.
        $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode(Cast::toOptInt($key)) : $keyAsString;

        if ($newValueAsString===$code)
        {
          // If the original value differs from the submitted value then the form control has been changed.
          if ($valueAsString!==$keyAsString)
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

    if (!isset($whiteListValues[$this->name]))
    {
      // No value has been submitted or a none white listed value has been submitted
      $this->value                  = null;
      $whiteListValues[$this->name] = null;
      if ($valueAsString!=='')
      {
        $changedInputs[$this->name] = $this;
      }
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
  /**
   * Returns the attributes for the input element.
   *
   * @param array $option The option.
   *
   * @return array
   */
  private function inputAttributes(array $option): array
  {
    $attributes = [];

    if (is_array($this->inputAttributesMap))
    {
      foreach ($this->inputAttributesMap as $key => $name)
      {
        if (isset($option[$key])) $attributes[$name] = $option[$key];
      }
    }

    $attributes['type'] = 'radio';
    $attributes['name'] = $this->submitName;

    if (!isset($attributes['id'])) $attributes['id'] = Html::getAutoId();

    return $attributes;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the attributes for the label element.
   *
   * @param array $option The option.
   *
   * @return array
   */
  private function labelAttributes(array $option): array
  {
    $attributes = [];

    if (is_array($this->labelAttributesMap))
    {
      foreach ($this->labelAttributesMap as $key => $name)
      {
        if (isset($option[$key])) $attributes[$name] = $option[$key];
      }
    }

    return $attributes;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
