<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Abc\Form\Formatter\Formatter;
use SetBased\Abc\Helper\Html;
use SetBased\Exception\LogicException;

/**
 * Abstract parent class for form controls of type:
 * <ul>
 * <li> text
 * <li> password
 * <li> hidden
 * <li> checkbox
 * <li> radio
 * <li> submit
 * <li> reset
 * <li> button
 * <li> file
 * <li> image
 * </ul>
 */
abstract class SimpleControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The cleaner to clean and/or translate (to machine format) the submitted value.
   *
   * @var Cleaner|null
   */
  protected $cleaner;

  /**
   * The formatter to format the value (from machine format) to the displayed value.
   *
   * @var Formatter|null
   */
  protected $formatter;

  /**
   * The label of this form control.
   *
   * @var string|null
   */
  protected $label;

  /**
   * The attributes for the label of this form control.
   *
   * @var string[]
   */
  protected $labelAttributes = [];

  /**
   * The position of the label of this form control.
   * <ul>
   * <li> 'pre'  The label will be inserted before the HML code of this form control.
   * <li> 'post' The label will be appended after the HML code of this form control.
   * </ul>
   *
   * @var string|null
   */
  protected $labelPosition;

  /**
   * The value of this form control.
   *
   * Only for a MultipleFileControl the value can be an array.
   *
   * @var string|array|null
   */
  protected $value;

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Object constructor.
   *
   * @param string|null $name The name of the form control.
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    // A simple form control must have a name.
    if ($this->name==='')
    {
      throw new LogicException('Name is empty');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds the value of this form control to the values.
   *
   * @param array $values
   */
  public function getSetValuesBase(array &$values): void
  {
    $values[$this->name] = $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @return string|array|null
   */
  public function getSubmittedValue()
  {
    return $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
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
   * Sets the attribute [autocomplete](http://www.w3schools.com/tags/att_input_autocomplete.asp).
   *
   * @param bool|null $value The attribute value.
   */
  public function setAttrAutoComplete(?bool $value): void
  {
    $this->attributes['autocomplete'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autofocus](http://www.w3schools.com/tags/att_input_autofocus.asp).
   *
   * @param bool|null $value The attribute value.
   */
  public function setAttrAutoFocus(?bool $value): void
  {
    $this->attributes['autofocus'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [disabled](http://www.w3schools.com/tags/att_input_disabled.asp).
   *
   * @param bool|null $value The attribute value.
   */
  public function setAttrDisabled(?bool $value): void
  {
    $this->attributes['disabled'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [form](http://www.w3schools.com/tags/att_input_form.asp).
   *
   * @param string|null $value The attribute value.
   */
  public function setAttrForm(?string $value): void
  {
    $this->attributes['form'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [list](http://www.w3schools.com/tags/att_input_list.asp).
   *
   * @param string|null $value The attribute value.
   */
  public function setAttrList(?String $value): void
  {
    $this->attributes['list'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [max](http://www.w3schools.com/tags/att_input_max.asp).
   *
   * @param string|null $value The attribute value.
   */
  public function setAttrMax(?string $value): void
  {
    $this->attributes['max'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [maxlength](http://www.w3schools.com/tags/att_input_maxlength.asp).
   *
   * @param int|null $value The attribute value.
   */
  public function setAttrMaxLength(?int $value): void
  {
    $this->attributes['maxlength'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [min](http://www.w3schools.com/tags/att_input_min.asp).
   *
   * @param string|null $value The attribute value.
   */
  public function setAttrMin(?string $value): void
  {
    $this->attributes['min'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [pattern](http://www.w3schools.com/tags/att_input_pattern.asp).
   *
   * @param string|null $value The attribute value.
   */
  public function setAttrPattern(?string $value): void
  {
    $this->attributes['pattern'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [placeholder](http://www.w3schools.com/tags/att_input_placeholder.asp).
   *
   * @param string|null $value The attribute value.
   */
  public function setAttrPlaceHolder(?String $value): void
  {
    $this->attributes['placeholder'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [readonly](http://www.w3schools.com/tags/att_input_readonly.asp).
   *
   * @param bool|null $value The attribute value.
   */
  public function setAttrReadOnly(?bool $value): void
  {
    $this->attributes['readonly'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [required](http://www.w3schools.com/tags/att_input_required.asp).
   *
   * @param bool|null $value The attribute value.
   */
  public function setAttrRequired(?bool $value): void
  {
    $this->attributes['required'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [size](http://www.w3schools.com/tags/att_input_size.asp).
   *
   * @param int|null $value The attribute value.
   */
  public function setAttrSize(?int $value): void
  {
    $this->attributes['size'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [step](http://www.w3schools.com/tags/att_input_step.asp).
   *
   * @param string|null $value The attribute value.
   */
  public function setAttrStep(?string $value): void
  {
    $this->attributes['step'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param Cleaner|null $cleaner The cleaner.
   */
  public function setCleaner(?Cleaner $cleaner)
  {
    $this->cleaner = $cleaner;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the formatter for this form control.
   *
   * @param Formatter|null $formatter The formatter.
   */
  public function setFormatter(?Formatter $formatter)
  {
    $this->formatter = $formatter;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of an attribute the label for this form control.
   *
   * The attribute is unset when the value is one of:
   * <ul>
   * <li> null
   * <li> false
   * <li> ''.
   * </ul>
   *
   * If attribute name is 'class' then the value is appended to the space separated list of classes.
   *
   * @param string      $name  The name of the attribute.
   * @param string|null $value The value for the attribute.
   */
  public function setLabelAttribute(string $name, ?string $value)
  {
    if ($value==='' || $value===null)
    {
      unset($this->labelAttributes[$name]);
    }
    else
    {
      if ($name=='class' && isset($this->labelAttributes[$name]))
      {
        $this->labelAttributes[$name] .= ' ';
        $this->labelAttributes[$name] .= $value;
      }
      else
      {
        $this->labelAttributes[$name] = $value;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of the label for this form control.
   *
   * @param string $htmlSnippet The (inner) label HTML snippet. It is the developer's responsibility that it is
   *                            valid HTML code.
   */
  public function setLabelHtml(?string $htmlSnippet): void
  {
    $this->label = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the position of the label of this form control.
   * <ul>
   * <li> 'pre'  The label will be inserted before the HML code of this form control.
   * <li> 'post' The label will be appended after the HML code of this form control.
   * <li> null No label will be generated for this form control.
   * </ul>
   *
   * @param string|null $position
   */
  public function setLabelPosition(?string $position): void
  {
    $this->labelPosition = $position;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of the abel for this form control.
   *
   * @param string|null $text The (inner) label text. Special characters are converted to HTML entities.
   */
  public function setLabelText(?string $text): void
  {
    $this->label = Html::txt2Html($text);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param string|null $value The new value for the form control.
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
    $this->setValue($values[$this->name] ?? null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for the label for this form control.
   *
   * @return string
   */
  protected function generateLabel(): string
  {
    return Html::generateElement('label', $this->labelAttributes, $this->label, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for a label for this form control to be appended after the HTML code of this form control.
   *
   * @return string
   */
  protected function generatePostfixLabel(): string
  {
    // Generate a postfix label, if required.
    if ($this->labelPosition=='post')
    {
      $ret = $this->generateLabel();
    }
    else
    {
      $ret = '';
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for a label for this form control te be inserted before the HTML code of this form control.
   *
   * @return string
   */
  protected function generatePrefixLabel(): string
  {
    // If a label must be generated make sure the form control and the label have matching 'id' and 'for' attributes.
    if (isset($this->labelPosition))
    {
      if (!isset($this->attributes['id']))
      {
        $id                           = Html::getAutoId();
        $this->attributes['id']       = $id;
        $this->labelAttributes['for'] = $id;
      }
      else
      {
        $this->labelAttributes['for'] = $this->attributes['id'];
      }
    }

    // Generate a prefix label, if required.
    if ($this->labelPosition=='pre')
    {
      $ret = $this->generateLabel();
    }
    else
    {
      $ret = '';
    }

    return $ret;
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
