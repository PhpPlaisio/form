<?php
declare(strict_types=1);

namespace Plaisio\Form\Walker;

/**
 * Class for walking the control tree when preparing the form.
 */
class PrepareWalker
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The CSS module class of form elements.
   *
   * @var string
   */
  private string $moduleClass;

  /**
   * The submit name of the control.
   *
   * @var string
   */
  private string $submitName;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $submitName  The submit name of the control.
   * @param string $moduleClass The CSS module class of form elements.
   */
  public function __construct(string $submitName, string $moduleClass)
  {
    $this->submitName  = $submitName;
    $this->moduleClass = $moduleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Descends into the control tree.
   *
   * @param string $submitName The submit name of the control.
   *
   * @return $this
   */
  public function descend(string $submitName): PrepareWalker
  {
    return new PrepareWalker($submitName, $this->moduleClass);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the CSS module class of form elements.
   *
   * @return string
   */
  public function getModuleClass(): string
  {
    return $this->moduleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submit name of the parent form control.
   *
   * @return string
   */
  public function getParentSubmitName(): string
  {
    return $this->submitName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the CSS module class of form elements.
   *
   * @param string $moduleClass The CSS module class of form elements.
   */
  public function setModuleClass(string $moduleClass): void
  {
    $this->moduleClass = $moduleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
