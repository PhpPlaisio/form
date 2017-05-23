<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form;

use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Form\Control\CompoundControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Validator\CompoundValidator;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\HtmlElement;
use SetBased\Exception\FallenException;

//----------------------------------------------------------------------------------------------------------------------
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
   * After a call to {@link validate} holds the names of the form controls which have valid one or more
   * validation tests.
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
  public function __construct($name = '')
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
  public static function hasScalars($array)
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
   * @return FieldSet
   *
   * @since 1.0.0
   * @api
   */
  public function addFieldSet($fieldSet)
  {
    $this->fieldSets->addFormControl($fieldSet);

    return $fieldSet;
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
  public function addValidator($validator)
  {
    $this->fieldSets->addValidator($validator);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a fieldset and appends this fieldset to the list of field sets of this form.
   *
   * @param string $type The class name of the fieldset which must be derived from class FieldSet. The following
   *                     aliases are implemented:
   *                     * fieldset: class FieldSet
   * @param string $name The name (which might be empty) of the fieldset.
   *
   * @return FieldSet
   */
  public function createFieldSet($type = 'fieldset', $name = '')
  {
    switch ($type)
    {
      case 'fieldset':
        $fieldset = new FieldSet($name);
        break;

      default:
        $fieldset = new $type($name);
    }

    $this->fieldSets->addFormControl($fieldset);

    return $fieldset;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   *
   * @since 1.0.0
   * @api
   */
  public function findFormControlByName($name)
  {
    return $this->fieldSets->findFormControlByName($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   *
   * @since 1.0.0
   * @api
   */
  public function findFormControlByPath($path)
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
  public function generate()
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
   * @note This method should only be invoked after method Form::loadSubmittedValues() has been invoked.
   *
   * @since 1.0.0
   * @api
   */
  public function getChangedControls()
  {
    return $this->changedControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   *
   * @since 1.0.0
   * @api
   */
  public function getFormControlByName($name)
  {
    return $this->fieldSets->getFormControlByName($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   *
   * @since 1.0.0
   * @api
   */
  public function getFormControlByPath($path)
  {
    return $this->fieldSets->getFormControlByPath($path);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all form controls which failed one or more validation tests.
   *
   * @return array A nested array of form control names (keys are form control names and (for complex form controls)
   *               values are arrays or (for simple form controls) true).
   * @note This method should only be invoked after method Form::validate() has been invoked.
   *
   * @since 1.0.0
   * @api
   */
  public function getInvalidControls()
  {
    return $this->invalidControls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the current values of the form controls of this form. This method can be invoked be for
   * loadSubmittedValues has been invoked. The values returned are the values set with {@link setValues},
   * {@link mergeValues}, and {@link SimpleControl.setValue}. These values might not be white listed.
   * After {@link loadSubmittedValues} has been invoked use getValues.
   *
   * @return array
   */
  public function getSetValues()
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
  public function getSubmittedValue()
  {
    return $this->getValues();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted values of all form controls.
   *
   * @note This method should only be invoked after method {@link loadSubmittedValues} has been invoked.
   *
   * @return array
   *
   * @since 1.0.0
   * @api
   */
  public function getValues()
  {
    return $this->values;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if the value of one or more submitted form controls have changed. Otherwise returns false.
   *
   * @note This method should only be invoked after method {@link loadSubmittedValues} has been invoked.
   */
  public function haveChangedInputs()
  {
    return !empty($this->changedControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the element (of type submit or image) has been submitted.
   *
   * @param string $name
   *
   * @return bool
   */
  public function isSubmitted($name)
  {
    switch ($this->attributes['method'])
    {
      case 'post':
        if (isset($_POST[$name])) return true;
        break;

      case 'get':
        if (isset($_GET[$name])) return true;
        break;

      default:
        throw new FallenException('method', $this->attributes['method']);
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the submitted values. The white listed values can be obtained with method {@link getValues).
   *
   * @since 1.0.0
   * @api
   */
  public function loadSubmittedValues()
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
   * are left on changed
   *
   * @param mixed $values The values as a nested array.
   */
  public function mergeValues($values)
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
  public function prepare()
  {
    $this->fieldSets->prepare('');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [action](http://www.w3schools.com/tags/att_form_action.asp). The default value is the URI which
   * was given the access the current page, i.e. $_SERVER['REQUEST_URI'].
   *
   * @param string $url The URL to send the form-data when this form is submitted.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAction($url)
  {
    $this->attributes['action'] = $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [autocomplete](http://www.w3schools.com/tags/att_form_autocomplete.asp). Possible values:
   * * Any value that evaluates to true will set the attribute to 'on'.
   * * Any value that evaluates to false will set the attribute to 'off'.
   * * Null will unset the attribute.
   *
   * @param mixed $autoComplete The auto complete.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAutoComplete($autoComplete)
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
   * @param string $encType The encoding type.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrEncType($encType)
  {
    $this->attributes['enctype'] = $encType;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [method](http://www.w3schools.com/tags/att_form_method.asp). Possible values:
   * * post (default)
   * * get
   *
   * @param string $method The method.
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrMethod($method)
  {
    $this->attributes['method'] = $method;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this form. The values of form controls for which no explicit value is set
   * are set to null.
   *
   * @param mixed $values The values as a nested array.
   */
  public function setValues($values)
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
  public function validate()
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
  protected function generateBody()
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
  protected function generateEndTag()
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
  protected function generateStartTag()
  {
    return Html::generateTag('form', $this->attributes);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
