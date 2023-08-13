<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Arranger\Arranger;
use Plaisio\Form\Arranger\LinearArranger;
use Plaisio\Form\Cleaner\CompoundCleaner;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Form\Walker\PrepareWalker;
use Plaisio\Helper\RenderWalker;
use SetBased\Exception\LogicException;

/**
 * Class for complex form controls. A complex form control consists of one of more form controls.
 */
class ComplexControl extends Control implements CompoundControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The cleaners to clean and/or translate (to machine format) the submitted values.
   *
   * @var CompoundCleaner[]
   */
  protected array $cleaners = [];

  /**
   * The child form controls of this form control.
   *
   * @var Control[]
   */
  protected array $controls = [];

  /**
   * The child form controls of this form control with invalid submitted values.
   *
   * @var Control[]
   */
  protected array $invalidControls = [];

  /**
   * The value of this form control, i.e. a nested array of the values of the child form controls.
   *
   * @var array
   */
  protected array $values = [];

  /**
   * The arranger for arranging the HTML code of the child form controls.
   *
   * @var Arranger
   */
  private Arranger $arranger;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name The name of this complex form control.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?string $name = '')
  {
    parent::__construct($name);

    $this->arranger = new LinearArranger();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a cleaner to this form control.
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
   * Adds a form control to this complex form control.
   *
   * @param Control $control The form control added.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function addFormControl(Control $control): self
  {
    $this->controls[] = $control;

    return $this;
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
    foreach ($this->controls as $control)
    {
      if ($control->name===$name)
      {
        return $control;
      }

      if ($control instanceof ComplexControl)
      {
        $tmp = $control->findFormControlByName($name);
        if ($tmp)
        {
          return $tmp;
        }
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

    // Path must start with a leading slash.
    if (!str_starts_with($path, '/'))
    {
      return null;
    }

    // Remove leading slash from the path.
    $relativePath = substr($path, 1);

    foreach ($this->controls as $control)
    {
      $parts = preg_split('/\/+/', $relativePath);

      if ($control->name===$parts[0])
      {
        if (count($parts)===1)
        {
          return $control;
        }
        else
        {
          if ($control instanceof ComplexControl)
          {
            array_shift($parts);
            $tmp = $control->findFormControlByPath('/'.implode('/', $parts));
            if ($tmp!==null)
            {
              return $tmp;
            }
          }
        }
      }
      elseif ($control->name==='' && ($control instanceof ComplexControl))
      {
        $tmp = $control->findFormControlByPath($path);
        if ($tmp!==null)
        {
          return $tmp;
        }
      }
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all child form controls of this complex form control.
   *
   * @return Control[]
   */
  public function getControls(): array
  {
    return $this->controls;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an array of all error messages of the child form controls of this complex form controls.
   *
   * @param bool $recursive Whether to fetch error messages of child form controls of this complex form control also.
   *
   * @return array|null
   *
   * @since 1.0.0
   * @api
   */
  public function getErrorMessages(bool $recursive = false): ?array
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

    if (is_array($this->errorMessages))
    {
      $ret = array_merge($ret, $this->errorMessages);
    }

    return (empty($ret)) ? null : $ret;
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
      throw new LogicException("Form control with name '%s' does not exist.", $name);
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
      throw new LogicException("Form control with path '%s' does not exist.", $path);
    }

    return $control;
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
    return $this->values;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function htmlControl(RenderWalker $walker): string
  {
    return $this->arranger->htmlArrange($walker, $this);
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
  public function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    $submitKey = $this->submitKey();
    $subWalker = $walker->descend($this->name, $submitKey);

    foreach ($this->cleaners as $cleaner)
    {
      $subWalker->clean($cleaner);
    }

    foreach ($this->controls as $control)
    {
      $control->loadSubmittedValuesBase($subWalker);
    }

    $this->values = $walker->ascend($this->name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex form control. The values of the form controls for which no
   * explicit value are set are not affected.
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
   * Prepares this complex form control for HTML code generation or loading submitted values.
   *
   * @param PrepareWalker $walker The object for walking the form control tree.
   *
   * @since 1.0.0
   * @api
   */
  public function prepare(PrepareWalker $walker): void
  {
    parent::prepare($walker);

    $subWalker = $walker->descend($this->submitName);
    foreach ($this->controls as $control)
    {
      $control->prepare($subWalker);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function searchSubmitHandler(array $submittedValues): ?string
  {
    if ($this->name==='')
    {
      $tmp1 = $submittedValues;
    }
    else
    {
      $submitKey = $this->submitKey();

      $tmp1 = $submittedValues[$submitKey] ?? [];
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
   * Sets the arranger for arranging the HTML code of the child form controls of the complex form control.
   *
   * @param Arranger $arranger The new arranger of this complex form control.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setArranger(Arranger $arranger): self
  {
    $this->arranger = $arranger;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the values of the form controls of this complex form control. The values of the form controls for which no
   * explicit value are set will be set to null.
   *
   * @param array|null $values The values as a nested array.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setValue(?array $values): self
  {
    foreach ($this->controls as $control)
    {
      $control->setValuesBase($values);
    }

    return $this;
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
   * Returns whether this complex form control is valid.
   *
   * First all validators of the child form controls of this complex form control are executed. Only if all child form
   * controls are valid the validators of this complex form control are executed.
   *
   * @param array $invalidFormControls A nested array of invalid form controls.
   *
   * @return bool
   */
  public function validateBase(array &$invalidFormControls): bool
  {
    $valid = true;

    // First, validate all child form controls.
    foreach ($this->controls as $control)
    {
      if (!$control->validateBase($invalidFormControls))
      {
        $valid                   = false;
        $this->invalidControls[] = $control;
        $control->setError(true);
      }
    }

    if ($valid)
    {
      // All the child form controls are valid. Validate this complex form control.
      foreach ($this->validators as $validator)
      {
        $valid = $validator->validate($this);
        if (!$valid)
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
