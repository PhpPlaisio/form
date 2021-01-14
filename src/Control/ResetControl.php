<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Helper\RenderWalker;

/**
 * Class for form controls of type [input:reset](http://www.w3schools.com/tags/tag_input.asp).
 */
class ResetControl extends SimpleControl
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
    $this->addClasses($walker->getClasses('reset'));

    return $this->generateInputElement('reset');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array $values Not used.
   */
  public function mergeValuesBase(array $values): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array|null $values Not used.
   */
  public function setValuesBase(?array $values): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
