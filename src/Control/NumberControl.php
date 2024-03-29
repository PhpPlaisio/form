<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Cleaner\AmbiguityCleaner;
use Plaisio\Form\Cleaner\PruneWhitespaceCleaner;
use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Control\Traits\LoadPlainText;
use Plaisio\Helper\RenderWalker;

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
  public function __construct(?string $name)
  {
    parent::__construct($name);

    $this->addCleaner(AmbiguityCleaner::get())
         ->addCleaner(PruneWhitespaceCleaner::get());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function htmlControl(RenderWalker $walker): string
  {
    $this->addControlClasses($walker, 'number');

    return $this->generateInputElement('number');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
