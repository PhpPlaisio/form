<?php
declare(strict_types=1);

namespace Plaisio\Form\Arranger;

use Plaisio\Form\Control\CompoundControl;
use Plaisio\Helper\RenderWalker;

/**
 * An arranger that arranges child form controls by concatenating their HTML code.
 */
class LinearArranger implements Arranger
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function htmlArrange(RenderWalker $walker, CompoundControl $parentControl): string
  {
    $html = '';
    foreach ($parentControl->getControls() as $control)
    {
      $html .= $control->htmlControl($walker);
    }

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
