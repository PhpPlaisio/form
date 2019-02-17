<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Validator\CompoundValidator;
use SetBased\Abc\Form\Validator\Validator;
use SetBased\Abc\Helper\Cast;
use SetBased\Abc\HtmlElement;
use SetBased\Abc\Obfuscator\Obfuscator;

/**
 * Abstract parent class for form controls.
 */
abstract class Control extends HtmlElement
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The list of error messages associated with this form control.
   *
   * @var string[]|null
   */
  protected $errorMessages;

  /**
   * The name of this form control.
   *
   * @var string
   */
  protected $name;

  /**
   * The obfuscator to obfuscate the (submitted) name of this form control.
   *
   * @var Obfuscator
   */
  protected $obfuscator;

  /**
   * The HTML code that will be appended after the HTML code of this form control.
   *
   * @var string
   */
  protected $postfix;

  /**
   * The HTML code that will be inserted before the HTML code of this form control.
   *
   * @var string
   */
  protected $prefix;

  /**
   * The submit name or name in the generated HTML code of this form control.
   *
   * @var string
   */
  protected $submitName;

  /**
   * The validators that will be used to validate this form control.
   *
   * @var Validator[]
   */
  protected $validators = [];

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Object constructor.
   *
   * @param string|null $name The (local) name of this form control.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?string $name = '')
  {
    $this->name = $name ?? '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a compound validator for this form control.
   *
   * @param Validator|CompoundValidator $validator
   *
   * @since 1.0.0
   * @api
   */
  public function addValidator($validator): void
  {
    $this->validators[] = $validator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the the error messages of this form control.
   *
   * @param bool $recursive
   *
   * @return string[]|null
   *
   * @since 1.0.0
   * @api
   */
  public function getErrorMessages(/** @noinspection PhpUnusedParameterInspection */
    $recursive = false): ?array
  {
    return $this->errorMessages;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  abstract public function getHtml(): string;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control in a table cell.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtmlTableCell(): string
  {
    return '<td class="control">'.$this->getHtml().'</td>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the name of this form control
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   *
   * @deprecated
   */
  public function getLocalName(): string
  {
    return $this->name;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the name of this form control.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getName(): string
  {
    return $this->name;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the value of this form control.
   *
   * @param array $values
   */
  public function getSetValuesBase(array &$values): void
  {
    // Nothing to do.
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submit name of this form control
   *
   * @return string
   */
  public function getSubmitName(): string
  {
    return $this->submitName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @return mixed
   *
   * @since 1.0.0
   * @api
   */
  abstract public function getSubmittedValue();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if this control is a hidden control (e.g. hidden, invisible, and constant control).
   * Otherwise, returns false.
   *
   * @since 1.0.0
   * @api
   */
  public function isHidden(): bool
  {
    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if this control can trigger a form submit.
   *
   * @since 1.0.0
   * @api
   */
  public function isSubmitTrigger(): bool
  {
    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the initial value(s) of this form control. The values of form controls for which no explicit value is set are
   * left unchanged.
   *
   * @see   mergeValuesBase
   *
   * @param array $values The initial values as nested arrays.
   *
   * @return void
   *
   * @since 1.0.0
   * @api
   */
  public function mergeValuesBase(array $values): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an error message to the list of error messages for this form control.
   *
   * @param string $message The error message.
   *
   * @since 1.0.0
   * @api
   */
  public function setErrorMessage(string $message): void
  {
    $this->errorMessages[] = $message;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the name of this form control.
   *
   * @param Obfuscator $obfuscator The obfuscator.
   *
   * @since 1.0.0
   * @api
   */
  public function setObfuscator(Obfuscator $obfuscator): void
  {
    $this->obfuscator = $obfuscator;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is inserted before the HTML code of this form control.
   *
   * @param string $htmlSnippet The HTML prefix.
   *
   * @since 1.0.0
   * @api
   */
  public function setPostfix(string $htmlSnippet): void
  {
    $this->postfix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is appended after the HTML code of this form control.
   *
   * @param string $htmlSnippet The HTML postfix.
   *
   * @since 1.0.0
   * @api
   */
  public function setPrefix(string $htmlSnippet): void
  {
    $this->prefix = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the initial value(s) of this form control. If a value for a form control is not specified the value of this
   * form control will be set to null.
   *
   * @see mergeValuesBase
   *
   * @param array|null $values The initial values as nested arrays.
   *
   * @return void
   */
  abstract public function setValuesBase(?array $values): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the obfuscated name of this form control. If this form control has no obfuscator the name of this form
   * control is returned.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function getObfuscatedName(): string
  {
    return ($this->obfuscator) ? $this->obfuscator->encode(Cast::toOptInt($this->name)) : $this->name;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values.
   *
   * @param array $submittedValues The submitted values.
   * @param array $whiteListValues The white listed values.
   * @param array $changedInputs   The form controls which values are changed by the form submit.
   *
   * @return void
   */
  abstract protected function loadSubmittedValuesBase(array $submittedValues,
                                                      array &$whiteListValues,
                                                      array &$changedInputs): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form control for HTML code generation or loading submitted values.
   *
   * @param string $parentSubmitName The submit name of the parent control.
   *
   * @since 1.0.0
   * @api
   */
  protected function prepare(string $parentSubmitName): void
  {
    $this->setSubmitName($parentSubmitName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the name of the method for handling the form when the form submit is triggered by this control. Otherwise,
   * return null.
   *
   * @param array $submittedValues The submitted values.
   *
   * @return string|null
   */
  protected function searchSubmitHandler(array $submittedValues): ?string
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the name this will be used for this form control when the form is submitted.
   *
   * @param string $parentSubmitName The submit name of the parent form control of this form control.
   */
  protected function setSubmitName(string $parentSubmitName): void
  {
    $submitName = ($this->obfuscator) ? $this->obfuscator->encode(Cast::toOptInt($this->name)) : $this->name;

    if ($parentSubmitName!=='')
    {
      if ($submitName!=='') $this->submitName = $parentSubmitName.'['.$submitName.']';
      else                  $this->submitName = $parentSubmitName;
    }
    else
    {
      $this->submitName = $submitName;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the validators of this form control.
   *
   * @param array $invalidFormControls The form controls with invalid submitted values.
   *
   * @return bool True if and only if the submitted values are valid.
   */
  abstract protected function validateBase(array &$invalidFormControls): bool;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
