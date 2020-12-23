<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Control\Traits\LoadPlainText;

/**
 * Class for form controls of type [input:number](http://www.w3schools.com/tags/tag_input.asp).
 */
class NumberControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElement;
  use LoadPlainText;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    return $this->generateInputElement('number');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
