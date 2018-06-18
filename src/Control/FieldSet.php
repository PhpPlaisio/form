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
   */
  public function generate(): string
  {
    $ret = $this->generateStartTag();

    $ret .= $this->generateLegend();

    $ret .= parent::generate();

    $ret .= $this->generateEndTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the end tag of this fieldset.
   *
   * @return string
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
   */
  protected function generateLegend(): string
  {
    if ($this->legend)
    {
      $ret = $this->legend->generate();
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
   */
  protected function generateStartTag(): string
  {
    return Html::generateTag('fieldset', $this->attributes);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
