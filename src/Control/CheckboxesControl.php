<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Obfuscator\Obfuscator;
use SetBased\Exception\LogicException;

/**
 * Class for form controls of with multiple checkboxes.
 */
class CheckboxesControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The key in $options holding the checked flag for the checkboxes.
   *
   * @var string
   */
  protected $checkedKey;

  /**
   * The map from the keys in the options to attribute names of the input elements.
   *
   * @var array|null
   */
  protected $inputAttributesMap;

  /**
   * The key in $options holding the keys for the checkboxes.
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
   * If true and only if true labels are HTML code. Otherwise special characters in the labels will be replaced with
   * HTML entities.
   *
   * @var bool
   */
  protected $labelIsHtml = false;

  /**
   * The key in $options holding the labels for the checkboxes.
   *
   * @var string
   */
  protected $labelKey;

  /**
   * The HTML snippet appended after each label for the checkboxes.
   *
   * @var string
   */
  protected $labelPostfix = '';

  /**
   * The HTML snippet inserted before each label for the checkboxes.
   *
   * @var string
   */
  protected $labelPrefix = '';

  /**
   * The options of this select box.
   *
   * @var array[]|null
   */
  protected $options;

  /**
   * The obfuscator for the names of the checkboxes.
   *
   * @var Obfuscator
   */
  protected $optionsObfuscator;

  /**
   * The values of the checkboxes.
   *
   * @var array
   */
  protected $value;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    // A checkboxes form control must have a name.
    if ($this->name==='')
    {
      throw new LogicException('Name is empty');
    }

    $this->attributes['class'] = 'checkboxes';
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
      foreach ($this->options as $option)
      {
        $inputAttributes = $this->inputAttributes($option);
        $labelAttributes = $this->labelAttributes($option);

        $labelAttributes['for'] = $inputAttributes['id'];

        // Get the (database) key of the option.
        $key  = $option[$this->keyKey];

        // If an obfuscator is installed compute the obfuscated code of the (database) ID.
        $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode(Cast::toOptInt($key)) : $key;

        $inputAttributes['name']    = ($this->submitName!=='') ? $this->submitName.'['.$code.']' : $code;
        $inputAttributes['checked'] = ($this->checkedKey!==null && !empty($option[$this->checkedKey]));

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
   * Adds the value of checked checkboxes the values with the name of this form control as key.
   *
   * @param array $values The values.
   */
  public function getSetValuesBase(array &$values): void
  {
    if ($this->name==='')
    {
      $tmp = &$values;
    }
    else
    {
      $values[$this->name] = [];
      $tmp                 = &$values[$this->name];
    }

    foreach ($this->options as $i => $option)
    {
      // Get the (database) ID of the option.
      $key = $option[$this->keyKey];

      // Get the original value (i.e. the option is checked or not).
      $tmp[$key] = (!empty($option[$this->checkedKey]));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
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
   * Set the values (i.e. checked or not checked) of the checkboxes of this form control.
   *
   * @param array $values the values.
   */
  public function mergeValuesBase(array $values): void
  {
    if ($this->name==='')
    {
      // Nothing to do.
      ;
    }
    elseif (isset($values[$this->name]))
    {
      $values = &$values[$this->name];
    }
    else
    {
      $values = null;
    }

    if ($values!==null)
    {
      foreach ($this->options as $id => $option)
      {
        if (array_key_exists($option[$this->keyKey], $values))
        {
          $this->options[$id][$this->checkedKey] = !empty($values[$option[$this->keyKey]]);
        }
      }
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
   * Sets the label prefix, e.g. the HTML code that is inserted before the HTML code of each label of the checkboxes.
   *
   * @param string $htmlSnippet The label prefix.
   *
   * @since 1.0.0
   * @api
   */
  public function setLabelPostfix(string $htmlSnippet): void
  {
    $this->labelPostfix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label postfix., e.g. the HTML code that is appended after the HTML code of each label of the checkboxes.
   *
   * @param string $htmlSnippet The label postfix.
   *
   * @since 1.0.0
   * @api
   */
  public function setLabelPrefix(string $htmlSnippet): void
  {
    $this->labelPrefix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for the checkboxes box.
   *
   * @param array[]|null $options    An array of arrays with the options.
   * @param string       $keyKey     The key holding the keys of the checkboxes.
   * @param string       $labelKey   The key holding the labels for the checkboxes.
   * @param string       $checkedKey The key holding the checked flag. Any
   *                                 [non-empty](http://php.net/manual/function.empty.php) value results that the
   *                                 checkbox is checked.
   *
   * @since 1.0.0
   * @api
   */
  public function setOptions(?array &$options,
                             string $keyKey,
                             string $labelKey,
                             string $checkedKey = 'abc_map_checked'): void
  {
    $this->options    = $options;
    $this->keyKey     = $keyKey;
    $this->labelKey   = $labelKey;
    $this->checkedKey = $checkedKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the names of the checkboxes. This method should be used when the names of the checkboxes
   * are database IDs.
   *
   * @param Obfuscator $obfuscator The obfuscator for the checkbox names.
   *
   * @since 1.0.0
   * @api
   */
  public function setOptionsObfuscator(Obfuscator $obfuscator): void
  {
    $this->optionsObfuscator = $obfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the checkboxes, a [non-empty](http://php.net/manual/function.empty.php) value will check a
   * checkbox.
   *
   * @param array|null $values The values.
   */
  public function setValuesBase(?array $values): void
  {
    if ($this->name==='')
    {
      // Nothing to do.
      ;
    }
    elseif (isset($values[$this->name]))
    {
      $values = &$values[$this->name];
    }
    else
    {
      $values = null;
    }

    foreach ($this->options as $id => $option)
    {
      $this->options[$id][$this->checkedKey] = !empty($values[$option[$this->keyKey]]);
    }
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

    foreach ($this->options as $i => $option)
    {
      // Get the (database) ID of the option.
      $key = $option[$this->keyKey];

      // If an obfuscator is installed compute the obfuscated code of the (database) ID.
      $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode(Cast::toOptInt($key)) : $key;

      // Get the original value (i.e. the option is checked or not).
      $value = $option[$this->checkedKey] ?? false;

      if ($submitKey!=='')
      {
        // Get the submitted value (i.e. the option is checked or not).
        $newValue = $submittedValues[$submitKey][$code] ?? false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($newValue)) $changedInputs[$this->name][$key] = $this;

        if (!empty($newValue))
        {
          $this->value[$key]                  = true;
          $whiteListValues[$this->name][$key] = true;
        }
        else
        {
          $this->value[$key]                  = false;
          $whiteListValues[$this->name][$key] = false;
        }
      }
      else
      {
        // Get the submitted value (i.e. the option is checked or not).
        $newValue = $submittedValues[$code] ?? false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($newValue)) $changedInputs[$key] = $this;

        if (!empty($newValue))
        {
          $this->value[$key]     = true;
          $whiteListValues[$key] = true;
        }
        else
        {
          $this->value[$key]     = false;
          $whiteListValues[$key] = false;
        }
      }

      $this->options[$i][$this->checkedKey] = $this->value[$key];
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
        $invalidFormControls[$this->name] = $this;
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

    $attributes['type'] = 'checkbox';

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
