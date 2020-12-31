<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Form\Walker\RenderWalker;

/**
 * Class for pseudo form controls for form controls of which the value is constant.
 */
class ConstantControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an empty string.
   *
   * @param RenderWalker $walker Unused.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(RenderWalker $walker): string
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true.
   *
   * @since 1.0.0
   * @api
   */
  public function isHidden(): bool
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * Note:
   * Always sets the white listed value to the value of this constant form control.
   * Never uses whitelisted values and never sets the changed controls.
   */
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    $walker->setWithListValue($this->name, $this->value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
