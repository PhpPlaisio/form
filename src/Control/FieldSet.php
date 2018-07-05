<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Form\Legend;
use SetBased\Abc\Helper\Html;

/**
 * Class for [fieldsets](http://www.w3schools.com/tags/tag_fieldset.asp).
 */
class FieldSet extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The legend of this fieldset.
   *
   * @var Legend
   */
  protected $legend;

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
  public function getHtml(): string
  {
    $ret = $this->generateStartTag();

    $ret .= $this->generateLegend();

    $ret .= parent::getHtml();

    $ret .= $this->generateEndTag();

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
  protected function generateEndTag(): string
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
  protected function generateLegend(): string
  {
    if ($this->legend)
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
  protected function generateStartTag(): string
  {
    return Html::generateTag('fieldset', $this->attributes);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
