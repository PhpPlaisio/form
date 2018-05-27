<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Obfuscator\Obfuscator;

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
   * The key in $options holding the disabled flag for the checkboxes.
   *
   * @var string|null
   */
  protected $disabledKey;

  /**
   * The key in $options holding the HTML ID attribute of the checkboxes.   *
   *
   * @var string|null
   */
  protected $idKey;

  /**
   * The key in $options holding the keys for the checkboxes.
   *
   * @var string
   */
  protected $keyKey;

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
   * @var array[]
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

  /**
   * The value that must be used when a submitted value is checked.
   *
   * @var mixed
   */
  protected $valueChecked = true;

  /**
   * The value that must be used when a submitted value is not checked.
   *
   * @var mixed
   */
  protected $valueUnchecked = false;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function generate()
  {
    $html = $this->prefix;
    $html .= Html::generateTag('span', $this->attributes);

    if (is_array($this->options))
    {
      $inputAttributes = ['type'     => 'checkbox',
                          'name'     => '',
                          'id'       => '',
                          'checked'  => false,
                          'disabled' => false];
      $labelAttributes = ['for' => &$inputAttributes['id']];

      foreach ($this->options as $option)
      {
        $code = ($this->optionsObfuscator) ?
          $this->optionsObfuscator->encode($option[$this->keyKey]) : $option[$this->keyKey];

        $inputAttributes['name']     = ($this->submitName!=='') ? $this->submitName.'['.$code.']' : $code;
        $inputAttributes['id']       = (isset($this->idKey) && isset($option[$this->idKey])) ? $option[$this->idKey] : Html::getAutoId();
        $inputAttributes['checked']  = (isset($this->checkedKey) && !empty($option[$this->checkedKey]));
        $inputAttributes['disabled'] = (isset($this->disabledKey) && !empty($option[$this->disabledKey]));

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
   * Adds the value of checked checkboxes the values with the name of this form control as key.
   *
   * @param array $values The values.
   */
  public function getSetValuesBase(&$values)
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
      $key = (string)$option[$this->keyKey];

      // Get the original value (i.e. the option is checked or not).
      $tmp[$key] = (!empty($option[$this->checkedKey]));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getSubmittedValue()
  {
    return $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the values (i.e. checked or not checked) of the checkboxes of this form control according to @a $values.
   *
   * @param array $values
   */
  public function mergeValuesBase($values)
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
   * Sets whether labels are HTML code.
   *
   * @param bool $labelIsHtml If true and only if true labels are HTML code.
   */
  public function setLabelIsHtml($labelIsHtml = true)
  {
    $this->labelIsHtml = $labelIsHtml;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label prefix, e.g. the HTML code that is inserted before the HTML code of each label of the checkboxes.
   *
   * @param string $htmlSnippet The label prefix.
   */
  public function setLabelPostfix($htmlSnippet)
  {
    $this->labelPostfix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the label postfix., e.g. the HTML code that is appended after the HTML code of each label of the checkboxes.
   *
   * @param string $htmlSnippet The label postfix.
   */
  public function setLabelPrefix($htmlSnippet)
  {
    $this->labelPrefix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the options for the checkboxes box.
   *
   * @param array[]     $options       An array of arrays with the options.
   * @param string      $keyKey        The key holding the keys of the checkboxes.
   * @param string      $labelKey      The key holding the labels for the checkboxes.
   * @param string|null $checkedKey    The key holding the checked flag. Any
   *                                   [non-empty](http://php.net/manual/function.empty.php) value results that the
   *                                   checkbox is checked.
   * @param string|null $disabledKey   The key holding the disabled flag. Any
   *                                   [non-empty](http://php.net/manual/function.empty.php) value results that the
   *                                   checkbox is disabled.
   * @param string|null $idKey         The key holding the HTML ID attribute of the checkboxes.
   */
  public function setOptions(&$options,
                             $keyKey,
                             $labelKey,
                             $checkedKey = 'abc_map_checked',
                             $disabledKey = null,
                             $idKey = null)
  {
    $this->options     = $options;
    $this->keyKey      = $keyKey;
    $this->labelKey    = $labelKey;
    $this->checkedKey  = $checkedKey;
    $this->disabledKey = $disabledKey;
    $this->idKey       = $idKey;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the names of the checkboxes. This method should be used when the names of the checkboxes
   * are database IDs.
   *
   * @param Obfuscator $obfuscator The obfuscator for the checkbox names.
   */
  public function setOptionsObfuscator($obfuscator)
  {
    $this->optionsObfuscator = $obfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the checkboxes, a [non-empty](http://php.net/manual/function.empty.php) value will check a
   * checkbox.
   *
   * @param array $values The values.
   */
  public function setValuesBase($values)
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
   * Sets the values that must be used when the submitted value is checked and not checked.
   *
   * By default values true (checked) and false (not checked) are used.
   *
   * If the values of this checkboxes are stored as not nullable integers in a database one might use 1 for checked
   * and 0 for not checked.
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
  protected function loadSubmittedValuesBase($submittedValues, &$whiteListValues, &$changedInputs)
  {
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    foreach ($this->options as $i => $option)
    {
      // Get the (database) ID of the option.
      $key = (string)$option[$this->keyKey];

      // If an obfuscator is installed compute the obfuscated code of the (database) ID.
      $code = ($this->optionsObfuscator) ? $this->optionsObfuscator->encode($key) : $key;

      // Get the original value (i.e. the option is checked or not).
      $value = $option[$this->checkedKey] ?? false;

      if ($submitName!=='')
      {
        // Get the submitted value (i.e. the option is checked or not).
        $newValue = $submittedValues[$submitName][$code] ?? false;

        // If the original value differs from the submitted value then the form control has been changed.
        if (empty($value)!==empty($newValue)) $changedInputs[$this->name][$key] = $this;

        if (!empty($newValue))
        {
          $this->value[$key]                  = $this->valueChecked;
          $whiteListValues[$this->name][$key] = $this->valueChecked;
        }
        else
        {
          $this->value[$key]                  = $this->valueUnchecked;
          $whiteListValues[$this->name][$key] = $this->valueUnchecked;
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
          $this->value[$key]     = $this->valueChecked;
          $whiteListValues[$key] = $this->valueChecked;
        }
        else
        {
          $this->value[$key]     = $this->valueUnchecked;
          $whiteListValues[$key] = $this->valueUnchecked;
        }
      }

      $this->options[$i][$this->checkedKey] = $this->value[$key];
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function validateBase(&$invalidFormControls)
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
}

//----------------------------------------------------------------------------------------------------------------------
