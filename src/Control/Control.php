<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Validator\CompoundValidator;
use Plaisio\Form\Validator\Validator;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Form\Walker\PrepareWalker;
use Plaisio\Helper\RenderWalker;
use Plaisio\Obfuscator\Obfuscator;
use SetBased\Helper\Cast;

/**
 * Abstract parent class for form controls.
 */
abstract class Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * CSS class for invalid form control.
   *
   * @var string
   */
  public static string $isErrorClass = 'is-error';

  /**
   * CSS class for mandatory form control.
   *
   * @var string
   */
  public static string $isMandatoryClass = 'is-mandatory';

  /**
   * Whether this control is erroneous.
   *
   * @var bool
   */
  protected bool $error = false;

  /**
   * The list of error messages associated with this form control.
   *
   * @var string[]|null
   */
  protected ?array $errorMessages = null;

  /**
   * The name of this form control.
   *
   * @var string
   */
  protected string $name;

  /**
   * The obfuscator to obfuscate the (submitted) name of this form control.
   *
   * @var Obfuscator|null
   */
  protected ?Obfuscator $obfuscator = null;

  /**
   * The HTML code that will be appended after the HTML code of this form control.
   *
   * @var string
   */
  protected string $postfix = '';

  /**
   * The HTML code that will be inserted before the HTML code of this form control.
   *
   * @var string
   */
  protected string $prefix = '';

  /**
   * The submit name or name in the generated HTML code of this form control.
   *
   * @var string
   */
  protected string $submitName;

  /**
   * The validators that will be used to validate this form control.
   *
   * @var Validator[]
   */
  protected array $validators = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name The name of this form control.
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
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function addValidator($validator): self
  {
    $this->validators[] = $validator;

    return $this;
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
   * @param RenderWalker $walker The object for walking the form control tree.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  abstract public function getHtml(RenderWalker $walker): string;

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
  abstract public function getSetValuesBase(array &$values): void;

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
   * Returns whether this control is erroneous.
   *
   * @return bool
   */
  public function isError(): bool
  {
    return $this->error;
  }

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
   * @param array $values The initial values as nested arrays.
   *
   * @return void
   *
   * @see   mergeValuesBase
   *
   * @since 1.0.0
   * @api
   */
  abstract public function mergeValuesBase(array $values): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets whether this control is erroneous.
   *
   * @param bool $error Whether this control is erroneous.
   *
   * @return Control
   */
  public function setError(bool $error): Control
  {
    $this->error = $error;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an error message to the list of error messages for this form control.
   *
   * @param string $message The error message.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setErrorMessage(string $message): self
  {
    $this->errorMessages[] = $message;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the obfuscator for the name of this form control.
   *
   * @param Obfuscator $obfuscator The obfuscator.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setObfuscator(Obfuscator $obfuscator): self
  {
    $this->obfuscator = $obfuscator;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is inserted before the HTML code of this form control.
   *
   * @param string $htmlSnippet The HTML prefix.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setPostfix(string $htmlSnippet): self
  {
    $this->postfix = $htmlSnippet;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the HTML code that is appended after the HTML code of this form control.
   *
   * @param string $htmlSnippet The HTML postfix.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setPrefix(string $htmlSnippet): self
  {
    $this->prefix = $htmlSnippet;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the initial value(s) of this form control. If a value for a form control is not specified the value of this
   * form control will be set to null.
   *
   * @param array|null $values The initial values as nested arrays.
   *
   * @return void
   *
   * @see mergeValuesBase
   */
  abstract public function setValuesBase(?array $values): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values.
   *
   * @param LoadWalker $walker The object for walking the form control tree.
   *
   * @return void
   */
  abstract protected function loadSubmittedValuesBase(LoadWalker $walker): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form control for HTML code generation or loading submitted values.
   *
   * @param PrepareWalker $walker The object for walking the form control tree.
   *
   * @since 1.0.0
   * @api
   */
  protected function prepare(PrepareWalker $walker): void
  {
    $this->setSubmitName($walker->getParentSubmitName());
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
    unset ($submittedValues);

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
    $submitKey = $this->submitKey();

    if ($parentSubmitName!=='')
    {
      if ($submitKey!=='') $this->submitName = $parentSubmitName.'['.$submitKey.']';
      else                 $this->submitName = $parentSubmitName;
    }
    else
    {
      $this->submitName = $submitKey;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the key under which the control is submitted in the submitted values.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function submitKey(): string
  {
    return ($this->obfuscator) ? $this->obfuscator->encode(Cast::toOptInt($this->name)) : $this->name;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the validators of this form control.
   *
   * @param array $invalidFormControls The form controls with invalid submitted values.
   *
   * @return bool
   */
  abstract protected function validateBase(array &$invalidFormControls): bool;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
