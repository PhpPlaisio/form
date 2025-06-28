<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Plaisio;

use Plaisio\Form\Control\Control;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Form\Walker\PrepareWalker;
use Plaisio\Helper\RenderWalker;

/**
 * Control for setting the submit-name of another form control.
 */
class TestControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the submit-name of a form control.
   *
   * @param Control $control The form control.
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
  public function htmlControl(RenderWalker $walker): string
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
