<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Legend;
use Plaisio\Form\Walker\RenderWalker;
use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;

/**
 * Class for [fieldsets](http://www.w3schools.com/tags/tag_fieldset.asp).
 */
class FieldSet extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use HtmlElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The legend of this fieldset.
   *
   * @var Legend|null
   */
  protected ?Legend $legend = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a legend for this fieldset.
   *
   * @param string $type The class name of the legend.
   *
   * @return Legend
   */
  public function createLegend($type = 'legend')
  {
    switch ($type)
    {
      case 'legend':
        $tmp = new Legend();
        break;

      default:
        $tmp = new $type();
    }

    $this->legend = $tmp;

    return $tmp;
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
    if (empty($this->controls)) return '';

    $this->addClass($walker->getModuleClass());
    if ($this->error)
    {
      $this->addClass(ComplexControl::$isErrorClass);
    }

    $ret = $this->getHtmlStartTag();
    $ret .= $this->getHtmlLegend();
    $ret .= parent::getHtml($walker);
    $ret .= $this->getHtmlEndTag();

    return $ret;
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
  protected function getHtmlLegend(): string
  {
    if ($this->legend!==null)
    {
      $ret = $this->legend->getHtml();
    }
    else
    {
      $ret = '';
    }

    return $ret;
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
