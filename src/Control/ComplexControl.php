<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Exception\LogicException;

/**
 * Class for complex form controls. A complex form control consists of one of more form controls.
 */
class ComplexControl extends Control implements CompoundControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The cleaner to clean and/or translate (to machine format) the submitted values.
   *
   * @var Cleaner|null
   */
  protected $cleaner;

  /**
   * The child form controls of this form control.
   *
   * @var Control[]
   */
  protected $controls = [];

  /**
   * The child form controls of this form control with invalid submitted values.
   *
   * @var Control[]
   */
  protected $invalidControls;

  /**
   * The value of this form control, i.e. a nested array of the values of the child form controls.
   *
   * @var mixed
   */
  protected $value;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name The (local) name of this complex form control.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?string $name = '')
  {
    parent::__construct($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a form control to this complex form control.
   *
   * @param Control $control The from control added.
   *
   * @since 1.0.0
   * @api
   */
  public function addFormControl(Control $control): void
  {
    $this->controls[] = $control;
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
    // Name must be string. Convert name to the string.
    $name = (string)$name;

    foreach ($this->controls as $control)
    {
      if ($control->name===$name) return $control;

      if ($control instanceof ComplexControl)
      {
        $tmp = $control->findFormControlByName($name);
        if ($tmp) return $tmp;
      }
    }

    return null;
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
    if ($path==='' || $path==='/')
    {
      return null;
    }

    // $path must start with a leading slash.
    if (substr($path, 0, 1)!='/')
    {
      return null;
    }

    // Remove leading slash from the path.
    $relativePath = substr($path, 1);

    foreach ($this->controls as $control)
    {
      $parts = preg_split('/\/+/', $relativePath);

      if ($control->name==$parts[0])
      {
        if (sizeof($parts)==1)
        {
          return $control;
        }
        else
        {
          if ($control instanceof ComplexControl)
          {
            array_shift($parts);
            $tmp = $control->findFormControlByPath('/'.implode('/', $parts));
            if ($tmp) return $tmp;
          }
        }
      }
      elseif ($control->name==='' && ($control instanceof ComplexControl))
      {
        $tmp = $control->findFormControlByPath($path);
        if ($tmp) return $tmp;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an array of all error messages of the child form controls of this complex form controls.
   *
   * @param bool $recursive If set error messages of complex child controls of this complex form controls are fetched
   *                        also.
   *
   * @return array|null
   *
   * @since 1.0.0
   * @api
   */
  public function getErrorMessages($recursive = false): ?array
  {
    $ret = [];
    if ($recursive)
    {
      foreach ($this->controls as $control)
      {
        $tmp = $control->getErrorMessages(true);
        if (is_array($tmp))
        {
          $ret = array_merge($ret, $tmp);
        }
      }
    }

    if (isset($this->errorMessages))
    {
      $ret = array_merge($ret, $this->errorMessages);
    }

    if (empty($ret)) $ret = null;

    return $ret;
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
    $control = $this->findFormControlByName($name);
    if ($control===null)
    {
      throw new LogicException("Form control with name '%s' does not exists.", $name);
    }

    return $control;
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
    $control = $this->findFormControlByPath($path);
    if ($control===null)
    {
      throw new LogicException("Form control with path '%s' does not exists.", $path);
    }

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $ret = $this->prefix;
    foreach ($this->controls as $control)
    {
      $ret .= $control->getHtml();
    }
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getSetValuesBase(array &$values): void
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

    foreach ($this->controls as $control)
    {
      $control->getSetValuesBase($tmp);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @returns array
   *
   * @since 1.0.0
   * @api
   */
  public function getSubmittedValue(): array
  {
    return $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the submitted values of this complex form control and this child form control are valid.
   * Otherwise, returns false.
   *
   * @return bool
   */
  public function isValid(): bool
  {
    return (empty($this->invalidControls) && empty($this->errorMessages));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function loadSubmittedValuesBase(array $submittedValues,
                                          array &$whiteListValues,
                                          array &$changedInputs): void
  {
    $submitKey = $this->submitKey();

    if ($this->name==='')
    {
      $tmp1 = $submittedValues;
      $tmp2 = &$whiteListValues;
      $tmp3 = &$changedInputs;
    }
    else
    {
      if (!isset($submittedValues[$submitKey])) $submittedValues[$submitKey] = [];
      if (!isset($whiteListValues[$this->name])) $whiteListValues[$this->name] = [];
      if (!isset($changedInputs[$this->name])) $changedInputs[$this->name] = [];

      $tmp1 = $submittedValues[$submitKey];
      $tmp2 = &$whiteListValues[$this->name];
      $tmp3 = &$changedInputs[$this->name];
    }

    foreach ($this->controls as $control)
    {
      if ($this->cleaner) $tmp1 = $this->cleaner->clean($tmp1);
      $control->loadSubmittedValuesBase($tmp1, $tmp2, $tmp3);
    }

    if ($this->name!=='')
    {
      if (empty($whiteListValues[$this->name])) unset($whiteListValues[$this->name]);
      if (empty($changedInputs[$this->name])) unset($changedInputs[$this->name]);
    }

    // Set the submitted values.
    $this->value = $tmp2;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are not affected.
   *
   * @param array|null $values The values as a nested array.
   */
  public function mergeValuesBase(?array $values): void
  {
    if ($this->name!=='')
    {
      $values = $values[$this->name] ?? null;
    }

    if ($values!==null)
    {
      foreach ($this->controls as $control)
      {
        $control->mergeValuesBase($values);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form complex control for HTML code generation or loading submitted values.
   *
   * @param string $parentSubmitName The submit name of the parent control.
   *
   * @since 1.0.0
   * @api
   */
  public function prepare(string $parentSubmitName): void
  {
    parent::prepare($parentSubmitName);

    foreach ($this->controls as $control)
    {
      $control->prepare($this->submitName);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function searchSubmitHandler(array $submittedValues): ?string
  {
    $submitKey = $this->submitKey();

    if ($this->name==='')
    {
      $tmp1 = $submittedValues;
    }
    else
    {
      if (!isset($submittedValues[$submitKey])) $submittedValues[$submitKey] = [];

      $tmp1 = $submittedValues[$submitKey];
    }

    foreach ($this->controls as $control)
    {
      $method = $control->searchSubmitHandler($tmp1);
      if ($method!==null)
      {
        return $method;
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param Cleaner|null $cleaner The cleaner.
   *
   * @since 1.0.0
   * @api
   */
  public function setCleaner(?Cleaner $cleaner): void
  {
    $this->cleaner = $cleaner;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex control. The values of form controls for which no explicit
   * value is set are set to null.
   *
   * @param mixed $values The values as a nested array.
   *
   * @since 1.0.0
   * @api
   */
  public function setValue($values): void
  {
    foreach ($this->controls as $control)
    {
      $control->setValuesBase($values);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function setValuesBase(?array $values): void
  {
    if ($this->name!=='')
    {
      $values = $values[$this->name] ?? null;
    }

    foreach ($this->controls as $control)
    {
      $control->setValuesBase($values);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes a validators on the child form controls of this form complex control. If  and only if all child form
   * controls are valid the validators of this complex control are executed.
   *
   * @param array $invalidFormControls A nested array of invalid form controls.
   *
   * @return bool True if and only if all form controls are valid.
   */
  public function validateBase(array &$invalidFormControls): bool
  {
    $valid = true;

    // First, validate all child form controls.
    foreach ($this->controls as $control)
    {
      if (!$control->validateBase($invalidFormControls))
      {
        $this->invalidControls[] = $control;
        $valid                   = false;
      }
    }

    if ($valid)
    {
      // All the child form controls are valid. Validate this complex form control.
      foreach ($this->validators as $validator)
      {
        $valid = $validator->validate($this);
        if ($valid!==true)
        {
          $invalidFormControls[] = $this;
          break;
        }
      }
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
