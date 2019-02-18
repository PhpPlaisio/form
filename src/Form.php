<?php
declare(strict_types=1);

namespace SetBased\Abc\Form;

use SetBased\Abc\Abc;
use SetBased\Abc\Exception\BadRequestException;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\SilentControl;
use SetBased\Exception\LogicException;
use SetBased\Exception\RuntimeException;

/**
 * Class for forms with protection against CSRF.
 *
 * This form class protects against CSRF attacks by means of State Full Double Submit Cookie.
 */
class Form extends RawForm
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set the generated form has protection against CSRF.
   *
   * @var bool
   */
  protected $csrfCheck;

  /**
   * FieldSet for all form control elements of type "hidden".
   *
   * @var FieldSet
   */
  protected $hiddenFieldSet;

  /**
   * <ul>
   * <li> true:  This form has been submitted and submitted values are valid.
   * <li> false: This form has been submitted and submitted values are not valid.
   * <li> null:  The form has not been submitted (or not yet executed).
   * </ul>
   *
   * @var bool|null
   */
  protected $valid;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name      The name of the form.
   * @param bool        $csrfCheck If true the generated form has protection against CSRF.
   */
  public function __construct(?string $name = '', bool $csrfCheck = true)
  {
    parent::__construct($name);

    $this->csrfCheck = $csrfCheck;

    // Create a fieldset for hidden form controls.
    $this->hiddenFieldSet = new FieldSet();
    $this->addFieldSet($this->hiddenFieldSet);

    // Set attribute for name (used by JavaScript).
    if ($name!=='') $this->setAttrData('name', $name);

    // Add hidden field for protection against CSRF.
    if ($this->csrfCheck) $this->hiddenFieldSet->addFormControl(new SilentControl('ses_csrf_token'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a hidden form control to the fieldset for hidden form controls.
   *
   * @param Control $control The hidden form control.
   */
  public function addHiddenFormControl(Control $control): void
  {
    $this->hiddenFieldSet->addFormControl($control);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Defends against CSRF attacks using State Full Double Submit Cookie.
   *
   * @throws RuntimeException
   */
  public function csrfCheck()
  {
    // Return immediately if CSRF check is disabled.
    if (!$this->csrfCheck) return;

    $control = $this->hiddenFieldSet->getFormControlByName('ses_csrf_token');

    // If CSRF tokens (from session and from submitted form) don't match: possible CSRF attack.
    $ses_csrf_token1 = Abc::$session->getCsrfToken();
    $ses_csrf_token2 = $control->getSubmittedValue();
    if ($ses_csrf_token1!==$ses_csrf_token2)
    {
      throw new BadRequestException('Possible CSRF attack');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The default form handler. It only handles method 'handleEchoForm'. Otherwise an exception is thrown.
   *
   * @param string $method The name of the method for handling the form state.
   */
  public function defaultHandler(string $method): void
  {
    switch ($method)
    {
      case 'handleEchoForm':
        $this->handleEchoForm();
        break;

      default:
        throw new LogicException("Unknown form method '%s'", $method);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes this form. Executes means:
   * <ul>
   * <li> If the form is submitted the submitted values are validated:
   *      <ul>
   *      <li> If the submitted values are valid the appropriated handler is returned.
   *      <li> Otherwise the form is shown.
   *      </ul>
   * <li> Otherwise the form is shown.
   * </ul>
   *
   * @return string The appropriate handler method.
   */
  public function execute(): string
  {
    $this->prepare();

    $handler = $this->searchSubmitHandler();
    if ($handler!==null)
    {
      $this->loadSubmittedValues();
      $this->valid = $this->validate();
      if (!$this->valid)
      {
        $handler = 'handleEchoForm';
      }
    }
    else
    {
      $handler = 'handleEchoForm';
    }

    return $handler;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the hidden fieldset of this form.
   *
   * @return FieldSet
   */
  public function getHiddenFieldSet(): FieldSet
  {
    return $this->hiddenFieldSet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns:
   * <ul>
   * <li> true:  This form has been submitted and submitted values are valid.
   * <li> false: This form has been submitted and submitted values are not valid.
   * <li> null:  The form has not been submitted (or not yet been executed).
   * </ul>
   *
   * @return bool|null
   */
  public function isValid(): ?bool
  {
    return $this->valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function loadSubmittedValues(): void
  {
    parent::loadSubmittedValues();

    $this->csrfCheck();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates and echos this form.
   *
   * This is the default method for generating and echoing a form.
   */
  protected function handleEchoForm(): void
  {
    echo $this->getHtml();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
