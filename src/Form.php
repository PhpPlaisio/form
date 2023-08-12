<?php
declare(strict_types=1);

namespace Plaisio\Form;

use Plaisio\Exception\BadRequestException;
use Plaisio\Form\Control\Control;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\SilentControl;
use Plaisio\Kernel\Nub;
use SetBased\Exception\LogicException;

/**
 * Class for forms with protection against CSRF.
 *
 * This form class protects against CSRF attacks by means of State Full Double Submit Cookie.
 */
class Form extends RawForm
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Whether the generated form must be protected against CSRF.
   *
   * @var bool
   */
  protected bool $csrfCheck;

  /**
   * FieldSet for all form control elements of type "hidden".
   *
   * @var FieldSet
   */
  protected FieldSet $hiddenFieldSet;

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
    $this->hiddenFieldSet->setSubClasses('fieldset-hidden');
    $this->addFieldSet($this->hiddenFieldSet);

    // Set attribute for name (used in JavaScript).
    if ($name!=='')
    {
      $this->setAttrData('name', $name);
    }

    // Add hidden field for protection against CSRF.
    if ($this->csrfCheck)
    {
      $this->hiddenFieldSet->addFormControl(new SilentControl('ses_csrf_token'));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a hidden form control to the fieldset for hidden form controls.
   *
   * @param Control $control The hidden form control.
   *
   * @return $this
   */
  public function addHiddenFormControl(Control $control): self
  {
    $this->hiddenFieldSet->addFormControl($control);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Defends against CSRF attacks using State Full Double Submit Cookie. Throws a BadRequestException in case of a
   * possible CSRF attack.
   *
   * @throws BadRequestException
   */
  public function csrfCheck(): void
  {
    // Return immediately if CSRF check is disabled.
    if (!$this->csrfCheck)
    {
      return;
    }

    $control = $this->hiddenFieldSet->getFormControlByName('ses_csrf_token');

    // If CSRF tokens (from session and from submitted form) don't match: possible CSRF attack.
    $sesCsrfToken1 = Nub::$nub->session->getCsrfToken();
    $sesCsrfToken2 = $control->getSubmittedValue();
    if ($sesCsrfToken1!==$sesCsrfToken2)
    {
      throw new BadRequestException('Possible CSRF attack');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The default form handler. Throws a logic exception when the method is not an empty string.
   *
   * @param string $method The name of the method for handling the form state.
   */
  public function defaultHandler(string $method): void
  {
    if ($method!=='')
    {
      throw new LogicException("Unknown form method '%s'", $method);
    }
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
   * @inheritdoc
   */
  public function loadSubmittedValues(): void
  {
    parent::loadSubmittedValues();

    $this->csrfCheck();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
