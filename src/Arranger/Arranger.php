<?php
declare(strict_types=1);

namespace Plaisio\Form\Arranger;

use Plaisio\Form\Control\CompoundControl;
use Plaisio\Helper\RenderWalker;

/**
 * Interface for classes that arrange the child form controls of a compound form control.
 */
interface Arranger
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the arranged HTML code of the child controls of a compound control.
   *
   * @param RenderWalker    $walker        The object for walking the form control tree.
   * @param CompoundControl $parentControl The parent control of which the child control must be arranged.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function htmlArrange(RenderWalker $walker, CompoundControl $parentControl): string;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
