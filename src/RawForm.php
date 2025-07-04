<?php
declare(strict_types=1);

namespace Plaisio\Form;

use Plaisio\Form\Arranger\Arranger;
use Plaisio\Form\Cleaner\CompoundCleaner;
use Plaisio\Form\Control\ComplexControl;
use Plaisio\Form\Control\CompoundControl;
use Plaisio\Form\Control\Control;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Validator\CompoundValidator;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Form\Walker\PrepareWalker;
use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;
use Plaisio\Helper\RenderWalker;
use Plaisio\Kernel\Nub;
use SetBased\Exception\FallenException;

/**
 * Class for generating [form](http://www.w3schools.com/tags/tag_form.asp) elements and processing submitted data.
 *
 * @property-read RenderWalker $renderWalker The render walker.
 */
#[\AllowDynamicProperties]
class RawForm implements CompoundControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use HtmlElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * After a call to {@link loadSubmittedValues} holds the names of the form controls of which the value has
   * changed.
   *
   * @var array
   */
  protected array $changedControls = [];

  /**
   * The cleaners to clean and/or translate (to machine format) the submitted values.
   *
   * @var CompoundCleaner[]
   */
  protected array $cleaners = [];

  /**
   * The list of error messages associated with this form control.
   *
   * @var string[]|null
   */
  protected ?array $errorMessages = null;

  /**
   * The field sets of this form.
   *
   * @var ComplexControl
   */
  protected ComplexControl $fieldSets;

  /**
   * After a call to {@link validate} holds the form controls which have failed one or more validations.
   *
   * @var array
   */
  protected array $invalidControls = [];

  /**
   * If true the form has been prepared (for executing of getting the HTML code).
   *
   * @var bool
   */
  protected bool $prepared = false;

  /**
   * <ul>
   * <li> true:  This form has been submitted and submitted values are valid.
   * <li> false: This form has been submitted and submitted values are not valid.
   * <li> null:  The form has not been submitted (or not yet executed).
   * </ul>
   *
   * @var bool|null
   */
  protected ?bool $valid = null;

  /**
   * After a call to {@link loadSubmittedValues} holds the white-listed submitted values.
   *
   * @var array
   */
  protected array $values = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name The name of the form.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?string $name = '')
  {
    $this->attributes['method'] = 'post';
    $this->fieldSets            = new ComplexControl($name);
    $this->renderWalker         = new RenderWalker('frm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if an array has one or more scalars. Otherwise, returns false.
   *
   * @param array $array The array.
   *
   * @return bool
   */
  public static function hasScalars(array $array): bool
  {
    $ret = false;
    foreach ($array as $tmp)
    {
      if ($tmp)
      {
        $ret = true;
        break;
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param CompoundCleaner $cleaner The cleaner.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function addCleaner(CompoundCleaner $cleaner): self
  {
    $this->cleaners[] = $cleaner;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a fieldset to the fieldsets of this form.
   *
   * @param FieldSet $fieldSet
   *
   * @return $this
   *
   * @api
   * @since 1.0.0
   */
  public function addFieldSet(FieldSet $fieldSet): self
  {
    $this->fieldSets->addFormControl($fieldSet);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a compound validator to list of compound validators for this form.
   *
   * @param CompoundValidator $validator
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function addValidator(CompoundValidator $validator): self
  {
    $this->fieldSets->addValidator($validator);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes this form. Executes means:
   * <ul>
   * <li> If the form is submitted the submitted values are validated:
   *      <ul>
   *      <li> If the submitted values are valid the appropriated handler is returned.
   *      <li> Otherwise, the form is shown.
   *      </ul>
   * <li> Otherwise, the form is shown.
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
        $handler = '';
      }
    }
    else
    {
      $handler = '';
    }

    return $handler;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function findFormControlByName(string $name): ?Control
  {
    return $this->fieldSets->findFormControlByName($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function findFormControlByPath(string $path): ?Control
  {
    return $this->fieldSets->findFormControlByPath($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all form control names of which the value has been changed.
   *
   * @return array A nested array of form control names. Keys are form control names and for complex form controls
   *               values are arrays, for simple form controls value is true.
   *
   * @note  This method should only be invoked after method Form::loadSubmittedValues() has been invoked.
   *
   * @since 1.0.0
   * @api
   */
  public function getChangedControls(): array
  {
    return $this->changedControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all child form controls (i.e. fieldsets) of this compound form control.
   *
   * @return Control[]
   *
   * @since 1.0.0
   * @api
   */
  public function getControls(): array
  {
    return $this->fieldSets->getControls();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getFormControlByName(string $name): Control
  {
    return $this->fieldSets->getFormControlByName($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getFormControlByPath(string $path): Control
  {
    return $this->fieldSets->getFormControlByPath($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all form controls which failed one or more validation tests.
   *
   * @return array A nested array of form control names. Keys are form control names and for complex form controls
   *               values are arrays and for simple form controls values is true.
   *
   * @note  This method should only be invoked after method Form::validate() has been invoked.
   *
   * @since 1.0.0
   * @api
   */
  public function getInvalidControls(): array
  {
    return $this->invalidControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the name of this form control
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getName(): string
  {
    return $this->fieldSets->getName();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the current values of the form controls of this form. This method can be invoked before
   * loadSubmittedValues has been invoked. The values returned are the values set with {@link setValues},
   * {@link mergeValues}, and {@link SimpleControl.setValue}. These values might not be whitelisted.
   * After {@link loadSubmittedValues} has been invoked use {@link getValues}.
   *
   * @return array
   *
   * @since 1.0.0
   * @api
   */
  public function getSetValues(): array
  {
    $ret = [];
    $this->fieldSets->getSetValuesBase($ret);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of all form controls. This method is an alias of {@link getValues}.
   *
   * @returns array
   *
   * @since 1.0.0
   * @api
   */
  public function getSubmittedValue(): array
  {
    return $this->getValues();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted values of all form controls.
   *
   * @note  This method should only be invoked after method {@link loadSubmittedValues} has been invoked.
   *
   * @return array
   *
   * @since 1.0.0
   * @api
   */
  public function getValues(): array
  {
    return $this->values;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether the value of one or more form controls have changed.
   *
   * @note  This method should only be invoked after the submitted values have been loaded.
   *
   * @since 1.0.0
   * @api
   */
  public function haveChangedControls(): bool
  {
    return !empty($this->changedControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of this form.
   *
   * Note: This method will not load submitted values
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function htmlForm(): string
  {
    if (!isset($this->attributes['action']))
    {
      $this->attributes['action'] = Nub::$nub->request->requestUri;
    }

    $this->prepare();
    $this->addClasses($this->renderWalker->getClasses('form'));

    $struct = ['tag'  => 'form',
               'attr' => $this->attributes,
               'html' => $this->fieldSets->htmlControl($this->renderWalker)];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of this form just as htmlForm(), however, when the form has been successfully submitted,
   * returns an empty string.
   *
   * Note: This method will not load submitted values
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function htmlFormEmptyIfValid(): string
  {
    return ($this->isValid()) ? '' : $this->htmlForm();
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
   * Sets the values of the form controls of this form. The values of the form controls for which no explicit value is
   * set are left unchanged.
   *
   * @param array|null $values The values as a nested array.
   *
   * @since 1.0.0
   * @api
   */
  public function mergeValues(?array $values): void
  {
    $this->fieldSets->mergeValuesBase($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the arranger for arranging the HTML code of the child form controls of the compound form control.
   *
   * @param Arranger $arranger The new arranger of this compound form control.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setArranger(Arranger $arranger): self
  {
    $this->fieldSets->setArranger($arranger);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [action](http://www.w3schools.com/tags/att_form_action.asp). The default value is the URI to the
   * current page, i.e., the HTTP header REQUEST_URI.
   *
   * @param string|null $url The URL to send the form-data when this form is submitted.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAction(?string $url): self
  {
    $this->attributes['action'] = $url;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autocomplete](http://www.w3schools.com/tags/att_form_autocomplete.asp).
   *
   * @param bool|null $autoComplete The auto complete value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAutoComplete(?bool $autoComplete): self
  {
    $this->attributes['autocomplete'] = $autoComplete;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [enctype](http://www.w3schools.com/tags/att_form_enctype.asp). Possible values:
   * * application/x-www-form-urlencoded (default)
   * * multipart/form-data
   * * text/plain
   *
   * @param string|null $encType The encoding type.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrEncType(?string $encType): self
  {
    $this->attributes['enctype'] = $encType;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [method](http://www.w3schools.com/tags/att_form_method.asp). Possible values:
   * * post (default)
   * * get
   *
   * @param string|null $method The method.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrMethod(?string $method): self
  {
    $this->attributes['method'] = $method;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an error message to the list of error messages for this form control.
   *
   * @param string $message The error message.
   *
   * @return $this
   */
  public function setErrorMessage(string $message)
  {
    $this->errorMessages[] = $message;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this form. The values of the form controls for which no explicit value is
   * set are set to null.
   *
   * @param array|null $values The values as a nested array.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setValues(?array $values): self
  {
    $this->fieldSets->setValuesBase($values);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values. The white listed values can be obtained with method {@link getValues).
   *
   * @since 1.0.0
   * @api
   */
  protected function loadSubmittedValues(): void
  {
    switch ($this->attributes['method'])
    {
      case 'post':
        $values = Nub::$nub->request->post;
        break;

      case 'get':
        $values = Nub::$nub->request->cgi;
        break;

      default:
        throw new FallenException('method', $this->attributes['method']);
    }

    foreach ($this->cleaners as $cleaner)
    {
      $values = $cleaner->clean($values);
    }

    $walker = new LoadWalker($values, $this->values, $this->changedControls, $this->getName());
    $this->fieldSets->loadSubmittedValuesBase($walker);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form for HTML code generation or loading submitted values.
   *
   * @since 1.0.0
   * @api
   */
  protected function prepare(): void
  {
    if (!$this->prepared)
    {
      $walker = new PrepareWalker('');
      $this->fieldSets->prepare($walker);

      $this->prepared = true;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If this form has been submitted returns the name of the method for handling this form. Otherwise, returns null.
   *
   * @return string|null
   * @api
   *
   * @since 1.0.0
   */
  protected function searchSubmitHandler(): ?string
  {
    switch ($this->attributes['method'])
    {
      case 'post':
        $values = Nub::$nub->request->post;
        break;

      case 'get':
        $values = Nub::$nub->request->cgi;
        break;

      default:
        throw new FallenException('method', $this->attributes['method']);
    }

    return $this->fieldSets->searchSubmitHandler($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates all form controls of this form against all their installed validation checks. After all, form controls
   * passed their validations, validates the form itself against all its installed validation checks.
   *
   * @return bool True if the submitted values are valid, false otherwise.
   *
   * @since 1.0.0
   * @api
   */
  protected function validate(): bool
  {
    return $this->fieldSets->validateBase($this->invalidControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
