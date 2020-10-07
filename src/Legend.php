<?php
declare(strict_types=1);

namespace Plaisio\Form;

use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;

/**
 * Class for generating [legend](http://www.w3schools.com/tags/tag_legend.asp) elements.
 */
class Legend extends HtmlElement
{
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
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    return Html::generateElement('legend', $this->attributes, $this->legend, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this legend.
   *
   * @param string|null $html The HTML of legend. It is the developer's responsibility that it is valid HTML code.
   *
   * @since 1.0.0
   * @api
   */
  public function setLegendHtml(?string $html): void
  {
    $this->legend = $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this legend.
   *
   * @param string|null $text The text of legend. Special characters will be converted to HTML entities.
   *
   * @since 1.0.0
   * @api
   */
  public function setLegendText(?string $text): void
  {
    $this->legend = Html::txt2Html($text);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
