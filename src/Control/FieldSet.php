<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Legend;
use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;
use Plaisio\Helper\RenderWalker;

/**
 * Class for [fieldsets](http://www.w3schools.com/tags/tag_fieldset.asp).
 */
class FieldSet extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use HtmlElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The additional CSS classes of this fieldset.
   *
   * @var string|array|null
   */
  private $additionalClasses = null;

  /**
   * The legend of this fieldset.
   *
   * @var Legend|null
   */
  private ?Legend $legend = null;

  /**
   * The CSS subclasses of this fieldset.
   *
   * @var string|array|null
   */
  private $subClasses = 'fieldset';

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(RenderWalker $walker): string
  {
    if (empty($this->controls)) return '';

    $this->addClasses($walker->getClasses($this->subClasses, $this->additionalClasses));
    if ($this->error)
    {
      $this->addClass(ComplexControl::$isErrorClass);
    }

    $ret = $this->getHtmlStartTag();
    $ret .= $this->getHtmlLegend($walker);
    $ret .= parent::getHtml($walker);
    $ret .= $this->getHtmlEndTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the additional CSS classes.
   *
   * @param array|string|null $additionalClasses The additional CSS classes.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAdditionalClasses($additionalClasses): FieldSet
  {
    $this->additionalClasses = $additionalClasses;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the legend of this fieldset.
   *
   * @param Legend|null $legend The legend.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setLegend(?Legend $legend): FieldSet
  {
    $this->legend = $legend;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the CSS subclasses.
   *
   * @param array|string|null $subClasses The CSS subclasses.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setSubClasses($subClasses): FieldSet
  {
    $this->subClasses = $subClasses;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the end tag of this fieldset.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function getHtmlEndTag(): string
  {
    return '</fieldset>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the legend for this fieldset.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function getHtmlLegend(RenderWalker $walker): string
  {
    return ($this->legend!==null) ? $this->legend->getHtml($walker) : '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the start tag of the fieldset.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function getHtmlStartTag(): string
  {
    return Html::generateTag('fieldset', $this->attributes);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
