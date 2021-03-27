<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

/**
 * Control for weights (for sorting).
 */
class WeightControl extends IntegerControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name The name of this form control.
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    $this->setAttrMin(-32768)
         ->setAttrMax(32767)
         ->setAttrSize(6);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
