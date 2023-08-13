<?php
declare(strict_types=1);

namespace Plaisio\Form\Arranger;

use Plaisio\Form\Control\CompoundControl;
use Plaisio\Helper\RenderWalker;

/**
 * An arranger that delegates the arranging of the HTML code of the child form controls to a callable.
 */
class ProxyArranger implements Arranger
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The callable for arranging the HTML code of the child form controls.
   *
   * @var callable
   */
  private $callable;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param callable $callable The callable for arranging the HTML code of the child form controls.
   */
  public function __construct(callable $callable)
  {
    $this->callable = $callable;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the arranged HTML code of the child form controls of a compound form control.
   *
   * @param RenderWalker    $walker        The object for walking the form control tree.
   * @param CompoundControl $parentControl The parent form control of which the child form control must be arranged.
   *
   * @return string
   */
  public function htmlArrange(RenderWalker $walker, CompoundControl $parentControl): string
  {
    return ($this->callable)($walker, $parentControl);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
