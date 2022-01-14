<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Obfuscator\Obfuscator;
use SetBased\Helper\Cast;

/**
 * Class for form controls of type [select](http://www.w3schools.com/tags/tag_select.asp).
 */
class SelectControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use Mutability;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set the first option in the select box with be an option with an empty label with value $emptyOption.
   *
   * @var string|null
   */
  protected ?string $emptyOption = null;

  /**
   * The key in $options holding the keys for the options in this select box.
   *
   * @var string
   */
  protected string $keyKey;

  /**
   * The key in $options holding the labels for the options in this select box.
   *
   * @var string
   */
  protected string $labelKey;

  /**
   * The map from the keys in the options to attribute names of the option elements.
   *
   * @var array|null
   */
  protected ?array $optionAttributesMap = null;

  /**
   * The options of this select box.
   *
   * @var array[]|null
   */
  protected ?array $options = null;

  /**
   * The obfuscator for the names of the options.
   *
   * @var Obfuscator|null
   */
  protected ?Obfuscator $optionsObfuscator = null;

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
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function htmlControl(RenderWalker $walker): string
  {
    $this->addControlClasses($walker, 'select');

    $this->attributes['name'] = $this->submitName;
    $valueAsString            = Cast::toManString($this->value, '');
    $inner                    = [];

    if ($this->emptyOption!==null)
    {
      $optionAttributes = ['value'    => $this->emptyOption,
                           'selected' => ($valueAsString===Cast::toManString($this->emptyOption, ''))];

      $inner[] = ['tag'  => 'option',
                  'attr' => $optionAttributes,
                  'text' => ' '];
    }

    if (is_array($this->options))
    {
      foreach ($this->options as $option)
      {
        $optionAttributes = $this->optionAttributes($option);

        $key         = $option[$this->keyKey];
        $keyAsString = Cast::toManString($key, '');

        $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode(Cast::toOptInt($key)) : $keyAsString;

        $optionAttributes['value']    = $code;
        $optionAttributes['selected'] = ($valueAsString===$keyAsString);

        $inner[] = ['tag'  => 'option',
                    'attr' => $optionAttributes,
                    'text' => $option[$this->labelKey]];
      }
    }

    $html = $this->prefix;
    $html .= $this->htmlPrefixLabel();
    $html .= Html::htmlNested(['tag'   => 'select',
                               'attr'  => $this->attributes,
                               'inner' => $inner]);
    $html .= $this->htmlPostfixLabel();
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an option with empty label as first option to this select box.
   *
   * @param string|null $emptyOption The value for the empty option. This value will not be obfuscated.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setEmptyOption(?string $emptyOption = ' '): self
  {
    $this->emptyOption = $emptyOption;

    return $this;
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
   *
   * @return $this
   */
  public function setOptionAttributesMap(?array $map): self
  {
    $this->optionAttributesMap = $map;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for this select box.
   *
   * @param array[]|null $options  The options of this select box.
   * @param string       $keyKey   The key holding the keys of the options.
   * @param string       $labelKey The key holding the labels for the options.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setOptions(?array $options, string $keyKey, string $labelKey): self
  {
    $this->options  = $options;
    $this->keyKey   = $keyKey;
    $this->labelKey = $labelKey;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the names (most likely the names are databases IDs) of the radio buttons.
   *
   * @param Obfuscator|null $obfuscator The obfuscator for the radio buttons.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setOptionsObfuscator(?Obfuscator $obfuscator): self
  {
    $this->optionsObfuscator = $obfuscator;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    if ($this->immutable===true)
    {
      $walker->setWithListValue($this->name, $this->value);
    }
    else
    {
      // Normalize current value as a string.
      $valueAsString = Cast::toManString($this->value, '');

      $submitKey      = $this->submitKey();
      $submittedValue = $walker->getSubmittedValue($submitKey);
      if ($submittedValue!==null)
      {
        // Normalize the submitted value as a string.
        $newValueAsString = Cast::toManString($submittedValue, '');

        if ($this->emptyOption!==null && $newValueAsString===$this->emptyOption)
        {
          $this->value = null;
          $walker->setWithListValue($this->name, null);
          if ($valueAsString!=='' && $valueAsString!==$this->emptyOption)
          {
            $walker->setChanged($this->name);
          }
        }
        else
        {
          if (is_array($this->options))
          {
            foreach ($this->options as $option)
            {
              // Get the key of the option.
              $key         = $option[$this->keyKey];
              $keyAsString = Cast::toManString($key, '');

              // If an obfuscator is installed compute the obfuscated code of the (database) ID.
              $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode(Cast::toOptInt($key)) : $keyAsString;

              if ($newValueAsString===$code)
              {
                // If the original value differs from the submitted value then the form control has been changed.
                if ($valueAsString!==$keyAsString)
                {
                  $walker->setChanged($this->name);
                }

                // Set the white listed value.
                $this->value = $key;
                $walker->setWithListValue($this->name, $key);

                break;
              }
            }
          }
        }
      }

      if ($walker->getWithListValue($this->name)===null)
      {
        // No value has been submitted or a none white listed value has been submitted
        $this->value = null;
        $walker->setWithListValue($this->name, null);
        if ($valueAsString!=='' && $valueAsString!==Cast::toManString($this->emptyOption, ''))
        {
          $walker->setChanged($this->name);
        }
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
        if (isset($option[$key]))
        {
          $attributes[$name] = $option[$key];
        }
      }
    }

    return $attributes;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
