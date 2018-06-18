<?php

namespace SetBased\Abc\Form;

use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\CompoundControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Validator\CompoundValidator;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\HtmlElement;
use SetBased\Exception\FallenException;

/**
 * Class for generating [form](http://www.w3schools.com/tags/tag_form.asp) elements and processing submitted data.
 */
class RawForm extends HtmlElement implements CompoundControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * After a call to {@link loadSubmittedValues} holds the names of the form controls of which the value has
   * changed.
   *
   * @var array
   */
  protected $changedControls = [];

  /**
   * The list of error messages associated with this form control.
   *
   * @var string[]|null
   */
  protected $errorMessages;

  /**
   * The field sets of this form.
   *
   * @var ComplexControl
   */
  protected $fieldSets;

  /**
   * The (form) validators for validating the submitted values for this form.
   *
   * @var CompoundValidator[]
   */
  protected $formValidators = [];

  /**
   * After a call to {@link validate} holds the form controls which have failed one or more validations.
   *
   * @var array
   */
  protected $invalidControls = [];

  /**
   * After a call to {@link loadSubmittedValues} holds the white-listed submitted values.
   *
   * @var array
   */
  protected $values = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $name The name of the form.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(string $name = '')
  {
    $this->attributes['action'] = $_SERVER['REQUEST_URI'] ?? '';
    $this->attributes['method'] = 'post';

    $this->fieldSets = new ComplexControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if array has one or more scalars. Otherwise, returns false.
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
      if (is_object($tmp))
      {
        $ret = true;
        break;
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a fieldset to the fieldsets of this form.
   *
   * @param FieldSet $fieldSet
   *
   * @since 1.0.0
   * @api
   */
  public function addFieldSet(FieldSet $fieldSet): void
  {
    $this->fieldSets->addFormControl($fieldSet);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a compound validator to list of compound validators for this form.
   *
   * @param CompoundValidator $validator
   *
   * @since 1.0.0
   * @api
   */
  public function addValidator(CompoundValidator $validator): void
  {
    $this->fieldSets->addValidator($validator);
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
   * Returns the HTML code of this form.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function generate(): string
  {
    $html = $this->generateStartTag();

    $html .= $this->generateBody();

    $html .= $this->generateEndTag();

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all form control names of which the value has been changed.
   *
   * @return array A nested array of form control names (keys are form control names and (for complex form controls)
   *               values are arrays or (for simple form controls) true).
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
   * @return array A nested array of form control names (keys are form control names and (for complex form controls)
   *               values are arrays or (for simple form controls) true).
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
   * Returns the current values of the form controls of this form. This method can be invoked before
   * loadSubmittedValues has been invoked. The values returned are the values set with {@link setValues},
   * {@link mergeValues}, and {@link SimpleControl.setValue}. These values might not be white listed.
   * After {@link loadSubmittedValues} has been invoked use {@link getValues}.
   *
   * @return array
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
   * Returns true if and only if the value of one or more submitted form controls have changed. Otherwise returns false.
   *
   * @note This method should only be invoked after method {@link loadSubmittedValues} has been invoked.
   */
  public function haveChangedInputs(): bool
  {
    return !empty($this->changedControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values. The white listed values can be obtained with method {@link getValues).
   *
   * @since 1.0.0
   * @api
   */
  public function loadSubmittedValues(): void
  {
    switch ($this->attributes['method'])
    {
      case 'post':
        $values = &$_POST;
        break;

      case 'get':
        $values = &$_GET;
        break;

      default:
        throw new FallenException('method', $this->attributes['method']);
    }

    // For all field sets load all submitted values.
    $this->fieldSets->loadSubmittedValuesBase($values, $this->values, $this->changedControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this form. The values of form controls for which no explicit value is set
   * are left unchanged.
   *
   * @param array $values The values as a nested array.
   */
  public function mergeValues(array $values): void
  {
    $this->fieldSets->mergeValuesBase($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form for HTML code generation or loading submitted values.
   *
   * @since 1.0.0
   * @api
   */
  public function prepare(): void
  {
    $this->fieldSets->prepare('');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [action](http://www.w3schools.com/tags/att_form_action.asp). The default value is the URI which
   * was given the access the current page, i.e. $_SERVER['REQUEST_URI'].
   *
   * @param string|null $url The URL to send the form-data when this form is submitted.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAction(?string $url): void
  {
    $this->attributes['action'] = $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autocomplete](http://www.w3schools.com/tags/att_form_autocomplete.asp).
   *
   * @param bool|null $autoComplete The auto complete value.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAutoComplete(?bool $autoComplete): void
  {
    $this->attributes['autocomplete'] = $autoComplete;
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
   * @since 1.0.0
   * @api
   */
  public function setAttrEncType(?string $encType): void
  {
    $this->attributes['enctype'] = $encType;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [method](http://www.w3schools.com/tags/att_form_method.asp). Possible values:
   * * post (default)
   * * get
   *
   * @param string|null $method The method.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrMethod(?string $method): void
  {
    $this->attributes['method'] = $method;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an error message to the list of error messages for this form control.
   *
   * @param string $message The error message.
   */
  public function setErrorMessage(string $message): void
  {
    $this->errorMessages[] = $message;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this form. The values of form controls for which no explicit value is set
   * are set to null.
   *
   * @param array|null $values The values as a nested array.
   */
  public function setValues(?array $values): void
  {
    $this->fieldSets->setValuesBase($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates all form controls of this form against all their installed validation checks. After all form controls
   * passed their validations validates the form itself against all its installed validation checks.
   *
   * @return bool True if the submitted values are valid, false otherwise.
   *
   * @since 1.0.0
   * @api
   */
  public function validate(): bool
  {
    return $this->fieldSets->validateBase($this->invalidControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of this form.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function generateBody(): string
  {
    return $this->fieldSets->generate();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the end tag of this form.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function generateEndTag(): string
  {
    return '</form>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the start tag of this form.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function generateStartTag(): string
  {
    return Html::generateTag('form', $this->attributes);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
