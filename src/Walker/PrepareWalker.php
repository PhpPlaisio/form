<?php
declare(strict_types=1);

namespace Plaisio\Form\Walker;

/**
 * Class for walking the form control tree when preparing the form.
 */
class PrepareWalker
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The submit-name of the form control.
   *
   * @var string
   */
  private string $submitName;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $submitName The submit-name of the form control.
   */
  public function __construct(string $submitName)
  {
    $this->submitName = $submitName;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Descends into the form control tree.
   *
   * @param string $submitName The submit-name of the form control.
   *
   * @return $this
   */
  public function descend(string $submitName): PrepareWalker
  {
    return new PrepareWalker($submitName);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submit-name of the parent form control.
   *
   * @return string
   */
  public function getParentSubmitName(): string
  {
    return $this->submitName;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
