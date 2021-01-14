<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Helper\RenderWalker;

/**
 * Class for form controls of type [input:hidden](http://www.w3schools.com/tags/tag_input.asp), however, the form
 * control is never marked as a changed form control and its value is never returned in the whitelist values.
 */
class SilentControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(RenderWalker $walker): string
  {
    $this->addControlClasses($walker, 'silent');

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
   */
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    $submitKey   = $this->submitKey();
    $this->value = $this->clean($walker->getSubmittedValue($submitKey));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
