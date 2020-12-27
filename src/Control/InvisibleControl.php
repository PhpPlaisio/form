<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Walker\LoadWalker;

/**
 * Class for form controls of type [input:hidden](http://www.w3schools.com/tags/tag_input.asp), however the submitted
 * value is never loaded.
 */
class InvisibleControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    return $this->generateInputElement('hidden');
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
    // Note: by definition the value of a input:invisible form control will not be changed, whatever is submitted.
    $walker->setWithListValue($this->name, $this->value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
