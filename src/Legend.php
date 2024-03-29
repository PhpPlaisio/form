<?php
declare(strict_types=1);

namespace Plaisio\Form;

use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;
use Plaisio\Helper\RenderWalker;

/**
 * Class for generating [legend](http://www.w3schools.com/tags/tag_legend.asp) elements.
 */
class Legend
{
  //--------------------------------------------------------------------------------------------------------------------
  use HtmlElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The inner HTML snippet of this legend.
   *
   * @var string|null
   */
  protected ?string $legend = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this legend.
   *
   * @param RenderWalker $walker The object for walking the form control tree.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function htmlLegend(RenderWalker $walker): string
  {
    $this->addClasses($walker->getClasses('legend'));

    return Html::htmlNested(['tag'  => 'legend',
                             'attr' => $this->attributes,
                             'html' => $this->legend]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this legend.
   *
   * @param string|null $html The HTML of legend. It is the developer's responsibility that it is valid HTML code.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setLegendHtml(?string $html): self
  {
    $this->legend = $html;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this legend.
   *
   * @param bool|int|float|string|null $text The text of legend. Special characters will be converted to HTML entities.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setLegendText(mixed $text): self
  {
    $this->legend = Html::txt2Html($text);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
