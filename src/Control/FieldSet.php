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
  private string|array|null $additionalClasses = null;

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
  private string|array|null $subClasses = 'fieldset';

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function htmlControl(RenderWalker $walker): string
  {
    if (empty($this->controls))
    {
      return '';
    }

    $this->addClasses($walker->getClasses($this->subClasses, $this->additionalClasses));
    if ($this->error)
    {
      $this->addClass(ComplexControl::$isErrorClass);
    }

    $inner = $this->htmlLegend($walker);
    $inner .= parent::htmlControl($walker);

    $struct = ['tag'  => 'fieldset',
               'attr' => $this->attributes,
               'html' => $inner];

    return Html::htmlNested($struct);
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
  public function setAdditionalClasses(array|string|null $additionalClasses): FieldSet
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
  public function setSubClasses(array|string|null $subClasses): FieldSet
  {
    $this->subClasses = $subClasses;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the legend for this fieldset.
   *
   * @param RenderWalker $walker The object for walking the form control tree.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function htmlLegend(RenderWalker $walker): string
  {
    return ($this->legend!==null) ? $this->legend->htmlLegend($walker) : '';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
