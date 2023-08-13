<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Cleaner\Cleaner;
use Plaisio\Form\Formatter\Formatter;
use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;
use Plaisio\Helper\RenderWalker;
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
  use HtmlElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The label will be inserted before the HML code of this form control.
   */
  const LABEL_POSITION_PRE = 1;

  /**
   * The label will be inserted before the HML code of this form control.
   */
  const LABEL_POSITION_POST = 2;

  /**
   * The cleaner to clean and/or translate (to machine format) the submitted value.
   *
   * @var Cleaner[]
   */
  protected array $cleaners = [];

  /**
   * The formatter to format the value (from machine format) to the displayed value.
   *
   * @var Formatter|null
   */
  protected ?Formatter $formatter = null;

  /**
   * The label of this form control.
   *
   * @var string|null
   */
  protected ?string $label = null;

  /**
   * The attributes for the label of this form control.
   *
   * @var string[]
   */
  protected array $labelAttributes = [];

  /**
   * The position of the label of this form control. One of:
   * <ul>
   * <li> C_LABEL_POSITION_PRE  The label will be inserted before the HML code of this form control.
   * <li> C_LABEL_POSITION_POST The label will be appended after the HML code of this form control.
   * </ul>
   *
   * @var int|null
   */
  protected ?int $labelPosition = null;

  /**
   * The value of this form control.
   *
   * Only for a MultipleFileControl the value can be an array.
   *
   * @var mixed
   */
  protected mixed $value = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name The name of the form control.
   *
   * @since 1.0.0
   * @api
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
   * Adds a cleaner to this form control.
   *
   * @param Cleaner $cleaner The cleaner.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function addCleaner(Cleaner $cleaner): self
  {
    $this->cleaners[] = $cleaner;

    return $this;
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
   * @since 1.0.0
   * @api
   */
  public function getSubmittedValue(): mixed
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
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAutoComplete(?bool $value): self
  {
    $this->attributes['autocomplete'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autofocus](http://www.w3schools.com/tags/att_input_autofocus.asp).
   *
   * @param bool|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAutoFocus(?bool $value): self
  {
    $this->attributes['autofocus'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [disabled](http://www.w3schools.com/tags/att_input_disabled.asp).
   *
   * @param bool|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrDisabled(?bool $value): self
  {
    $this->attributes['disabled'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [form](http://www.w3schools.com/tags/att_input_form.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrForm(?string $value): self
  {
    $this->attributes['form'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [list](http://www.w3schools.com/tags/att_input_list.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrList(?string $value): self
  {
    $this->attributes['list'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [max](http://www.w3schools.com/tags/att_input_max.asp).
   *
   * @param string|int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrMax(mixed $value): self
  {
    $this->attributes['max'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [maxlength](http://www.w3schools.com/tags/att_input_maxlength.asp).
   *
   * @param int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrMaxLength(?int $value): self
  {
    $this->attributes['maxlength'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [min](http://www.w3schools.com/tags/att_input_min.asp).
   *
   * @param string|int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrMin(mixed $value): self
  {
    $this->attributes['min'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [pattern](http://www.w3schools.com/tags/att_input_pattern.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrPattern(?string $value): self
  {
    $this->attributes['pattern'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [placeholder](http://www.w3schools.com/tags/att_input_placeholder.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrPlaceHolder(?string $value): self
  {
    $this->attributes['placeholder'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [readonly](http://www.w3schools.com/tags/att_input_readonly.asp).
   *
   * @param bool|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrReadOnly(?bool $value): self
  {
    $this->attributes['readonly'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [required](http://www.w3schools.com/tags/att_input_required.asp).
   *
   * @param bool|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrRequired(?bool $value): self
  {
    $this->attributes['required'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [size](http://www.w3schools.com/tags/att_input_size.asp).
   *
   * @param int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrSize(?int $value): self
  {
    $this->attributes['size'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [step](http://www.w3schools.com/tags/att_input_step.asp).
   *
   * @param string|int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrStep(mixed $value): self
  {
    $this->attributes['step'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the formatter for this form control.
   *
   * @param Formatter|null $formatter The formatter.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setFormatter(?Formatter $formatter): self
  {
    $this->formatter = $formatter;

    return $this;
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
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setLabelAttribute(string $name, ?string $value): self
  {
    if ($value==='' || $value===null)
    {
      unset($this->labelAttributes[$name]);
    }
    else
    {
      if ($name==='class' && isset($this->labelAttributes[$name]))
      {
        $this->labelAttributes[$name] .= ' ';
        $this->labelAttributes[$name] .= $value;
      }
      else
      {
        $this->labelAttributes[$name] = $value;
      }
    }

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of the label for this form control.
   *
   * @param string|null $htmlSnippet The (inner) label HTML snippet. It is the developer's responsibility that it is
   *                                 valid HTML code.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setLabelHtml(?string $htmlSnippet): self
  {
    $this->label = $htmlSnippet;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the position of the label of this form control.
   * <ul>
   * <li> C_LABEL_POSITION_PRE  The label will be inserted before the HML code of this form control.
   * <li> C_LABEL_POSITION_POST The label will be appended after the HML code of this form control.
   * <li> null                  No label will be generated for this form control.
   * </ul>
   *
   * @param int|null $position
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setLabelPosition(?int $position): self
  {
    $this->labelPosition = $position;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of the abel for this form control.
   *
   * @param bool|int|float|string|null $text The (inner) label text. Special characters are converted to HTML entities.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setLabelText(mixed $text): self
  {
    $this->label = Html::txt2Html($text);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param mixed $value The new value for the form control.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setValue(mixed $value): self
  {
    $this->value = $value;

    return $this;
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
   * Adds all control classes to this form control.
   *
   * @param RenderWalker $walker The object for walking the form control tree.
   * @param string       $type   The type of this from control (not to be confused with type attribute of input
   *                             element).
   */
  protected function addControlClasses(RenderWalker $walker, string $type): void
  {
    $this->addClasses($walker->getClasses($type));
    if ($this->error)
    {
      $this->addClass(ComplexControl::$isErrorClass);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Applies the cleaners on the submitted value.
   *
   * @param mixed $value The submitted value.
   *
   * @return mixed
   */
  protected function clean(mixed $value): mixed
  {
    foreach ($this->cleaners as $cleaner)
    {
      $value = $cleaner->clean($value);
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for the label for this form control.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function htmlLabel(): string
  {
    return Html::htmlNested(['tag'  => 'label',
                             'attr' => $this->labelAttributes,
                             'html' => $this->label]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns HTML code for a label for this form control to be appended after the HTML code of this form control.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function htmlPostfixLabel(): string
  {
    if ($this->labelPosition===self::LABEL_POSITION_POST)
    {
      $html = $this->htmlLabel();
    }
    else
    {
      $html = '';
    }

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for a label for this form control te be inserted before the HTML code of this form control.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function htmlPrefixLabel(): string
  {
    // If a label must be generated make sure the form control and the label have matching 'id' and 'for' attributes.
    if ($this->labelPosition!==null)
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

    if ($this->labelPosition===self::LABEL_POSITION_PRE)
    {
      $html = $this->htmlLabel();
    }
    else
    {
      $html = '';
    }

    return $html;
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
      if (!$valid)
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
