<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Cleaner\AmbiguityCleaner;
use Plaisio\Form\Cleaner\PruneWhitespaceCleaner;
use Plaisio\Form\Cleaner\UrlCleaner;
use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Control\Traits\LoadPlainText;
use Plaisio\Helper\RenderWalker;

/**
 * Class for form controls of type [input:url](https://www.w3schools.com/tags/att_input_type_url.asp).
 */
class UrlControl extends SimpleControl
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

    $this->addCleaner(AmbiguityCleaner::get());
    $this->addCleaner(PruneWhitespaceCleaner::get());
    $this->addCleaner(UrlCleaner::get());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(RenderWalker $walker): string
  {
    $this->addControlClasses($walker, 'url');

    return $this->generateInputElement('url');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
