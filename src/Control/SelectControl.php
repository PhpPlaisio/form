<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Obfuscator\Obfuscator;

/**
 * Class for form controls of type [select](http://www.w3schools.com/tags/tag_select.asp).
 */
class SelectControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set the first option in the select box with be an option with an empty label with value $emptyOption.
   *
   * @var string|null
   */
  protected $emptyOption;

  /**
   * The key in $options holding the keys for the options in this select box.
   *
   * @var string
   */
  protected $keyKey;

  /**
   * The key in $options holding the labels for the options in this select box.
   *
   * @var string
   */
  protected $labelKey;

  /**
   * The map from the keys in the options to attribute names of the option elements.
   *
   * @var array|null
   */
  protected $optionAttributesMap;

  /**
   * The options of this select box.
   *
   * @var array[]|null
   */
  protected $options;

  /**
   * The obfuscator for the names of the options.
   *
   * @var Obfuscator|null
   */
  protected $optionsObfuscator;

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $this->attributes['name'] = $this->submitName;

    $html = $this->prefix;
    $html .= $this->getHtmlPrefixLabel();
    $html .= Html::generateTag('select', $this->attributes);

    // Add an empty option, if necessary.
    if ($this->emptyOption!==null)
    {
      $optionAttributes = ['value'    => $this->emptyOption,
                           'selected' => ((string)$this->value===(string)$this->emptyOption)];

      $html .= Html::generateElement('option', $optionAttributes, ' ');
    }

    if (is_array($this->options))
    {
      foreach ($this->options as $option)
      {
        $optionAttributes = $this->optionAttributes($option);

        // Get the (database) key of the option.
        $key = $option[$this->keyKey];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode(Cast::toOptInt($key)) : $key;

        $optionAttributes['value']    = $code;
        $optionAttributes['selected'] = ((string)$this->value===(string)$key);

        $html .= Html::generateElement('option', $optionAttributes, $option[$this->labelKey]);
      }
    }

    $html .= '</select>';
    $html .= $this->getHtmlPostfixLabel();
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the options of this select box as set by {@link setOptions}.
   *
   * @return array[]
   *
   * @since 1.0.0
   * @api
   */
  public function getOptions(): array
  {
    return $this->options ?? [];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an option with empty label as first option to this select box.
   *
   * @param string|null $emptyOption The value for the empty option. This value will not be obfuscated.
   *
   * @since 1.0.0
   * @api
   */
  public function setEmptyOption(?string $emptyOption = ' '): void
  {
    $this->emptyOption = $emptyOption;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the map from the keys in the options to attribute names of the option element.
   *
   * Note the following attributes will ignored:
   * <ul>
   * <li> checked
   * <li> value
   * </ul>
   *
   * @param array|null $map The map.
   */
  public function setOptionAttributesMap(?array $map)
  {
    $this->optionAttributesMap = $map;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for this select box.
   *
   * @param array[]|null $options  The options of this select box.
   * @param string       $keyKey   The key holding the keys of the options.
   * @param string       $labelKey The key holding the labels for the options.
   *
   * @since 1.0.0
   * @api
   */
  public function setOptions(?array &$options, string $keyKey, string $labelKey)
  {
    $this->options  = $options;
    $this->keyKey   = $keyKey;
    $this->labelKey = $labelKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the names (most likely the names are databases IDs) of the radio buttons.
   *
   * @param Obfuscator|null $obfuscator The obfuscator for the radio buttons.
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
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(array $submittedValues,
                                             array &$whiteListValues,
                                             array &$changedInputs): void
  {
    $submitKey = $this->submitKey();

    // Normalize current value as a string.
    $value = (string)$this->value;

    if (isset($submittedValues[$submitKey]))
    {
      // Normalize the submitted value as a string.
      $newValue = (string)$submittedValues[$submitKey];

      if ($this->emptyOption!==null && $newValue===$this->emptyOption)
      {
        $this->value                  = null;
        $whiteListValues[$this->name] = null;
        if ($value!=='' && $value!==$this->emptyOption)
        {
          $changedInputs[$this->name] = $this;
        }
      }
      else
      {
        if (is_array($this->options))
        {
          foreach ($this->options as $option)
          {
            // Get the key of the option.
            $key = $option[$this->keyKey];

            // If an obfuscator is installed compute the obfuscated code of the (database) ID.
            $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode(Cast::toOptInt($key)) : (string)$key;

            if ($newValue===$code)
            {
              // If the original value differs from the submitted value then the form control has been changed.
              if ($value!==(string)$key)
              {
                $changedInputs[$this->name] = $this;
              }

              // Set the white listed value.
              $this->value                  = $key;
              $whiteListValues[$this->name] = $key;

              break;
            }
          }
        }
      }
    }

    if (!isset($whiteListValues[$this->name]))
    {
      // No value has been submitted or a none white listed value has been submitted
      $this->value                  = null;
      $whiteListValues[$this->name] = null;
      if ($value!=='' && $value!==(string)$this->emptyOption)
      {
        $changedInputs[$this->name] = $this;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the attributes for the option element.
   *
   * @param array $option The option.
   *
   * @return array
   */
  private function optionAttributes(array $option): array
  {
    $attributes = [];

    if (is_array($this->optionAttributesMap))
    {
      foreach ($this->optionAttributesMap as $key => $name)
      {
        if (isset($option[$key])) $attributes[$name] = $option[$key];
      }
    }

    return $attributes;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
