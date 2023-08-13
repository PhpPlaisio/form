<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;
use Plaisio\Helper\RenderWalker;

/**
 * A complex form control that wraps its child form controls in an HTML element (default a div container).
 */
class WrapperControl extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use HtmlElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The tag of the wrapper element.
   *
   * @var string
   */
  private string $tag = 'div';

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the tag of the wrapper element.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getTag(): string
  {
    return $this->tag;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function htmlControl(RenderWalker $walker): string
  {
    $struct = ['tag'  => $this->tag,
               'attr' => $this->getAttributes(),
               'html' => parent::htmlControl($walker)];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the tag of the wrapper element.
   *
   * @param string $tag The tag of the wrapper element.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setTag(string $tag): self
  {
    $this->tag = $tag;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
