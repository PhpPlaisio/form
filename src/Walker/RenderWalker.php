<?php
declare(strict_types=1);

namespace Plaisio\Form\Walker;

/**
 *  Class for walking the control tree when rendering the form into HTML.
 */
class RenderWalker
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The CSS module class.
   *
   * @var string
   */
  private string $moduleClass;

  /**
   * The CSS sub-module class.
   *
   * @var string|null
   */
  private ?string $subModuleClass;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string      $moduleClass    The CSS module class.
   * @param string|null $subModuleClass The CSS sub-module class.
   */
  public function __construct(string $moduleClass, ?string $subModuleClass = null)
  {
    $this->moduleClass    = $moduleClass;
    $this->subModuleClass = $subModuleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the module, sub-module and sub-classes for an HTML element.
   *
   * @param string|null $subClass The CSS sub-class.
   *
   * @return string[]
   */
  public function getClasses(?string $subClass = null): array
  {
    $classes = [$this->moduleClass];
    if ($this->subModuleClass!==null)
    {
      $classes[] = $this->subModuleClass;
    }
    if ($subClass!==null)
    {
      $classes[] = $this->moduleClass.'-'.$subClass;
    }

    return $classes;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the module class.
   *
   * @return string
   */
  public function getModuleClass(): string
  {
    return $this->moduleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets CSS module class.
   *
   * @param string $moduleClass The CSS module class.
   *
   * @return $this
   */
  public function setModuleClass(string $moduleClass): self
  {
    $this->moduleClass = $moduleClass;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the CSS sub-module class.
   *
   * @param string|null $subModuleClass The CSS sub-module class.
   *
   * @return $this
   */
  public function setSubModuleClass(?string $subModuleClass): self
  {
    $this->subModuleClass = $subModuleClass;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
