<?php
declare(strict_types=1);

namespace Plaisio\Form\Test;

use Plaisio\Form\Control\Control;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Form\Walker\PrepareWalker;
use Plaisio\Form\Walker\RenderWalker;

/**
 * Control for setting the submit name of another control.
 */
class TestControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the submit name of a control.
   *
   * @param Control $control The control.
   */
  public static function fixSubmitName(Control $control): void
  {
    $walker = new PrepareWalker('');

    $control->prepare($walker);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function getHtml(RenderWalker $walker): string
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
