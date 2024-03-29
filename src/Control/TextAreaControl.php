<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Cleaner\AmbiguityCleaner;
use Plaisio\Form\Cleaner\TrimWhitespaceCleaner;
use Plaisio\Form\Control\Traits\LoadPlainText;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;

/**
 * Class for form controls of type [textarea](http://www.w3schools.com/tags/tag_textarea.asp).
 */
class TextAreaControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
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
         ->addCleaner(TrimWhitespaceCleaner::get());
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
    $this->addControlClasses($walker, 'textarea');

    $this->attributes['name'] = $this->submitName;

    $html = $this->htmlPrefixLabel();
    $html .= Html::htmlNested(['tag'  => 'textarea',
                               'attr' => $this->attributes,
                               'text' => $this->value]);
    $html .= $this->htmlPostfixLabel();

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [cols](http://www.w3schools.com/tags/att_textarea_cols.asp).
   *
   * @param int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrCols(?int $value): self
  {
    $this->attributes['cols'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [rows](http://www.w3schools.com/tags/att_textarea_rows.asp).
   *
   * @param int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrRows(?int $value): self
  {
    $this->attributes['rows'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [wrap](http://www.w3schools.com/tags/att_textarea_wrap.asp). Possible values:
   * * soft
   * * hard
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrWrap(?string $value): self
  {
    $this->attributes['wrap'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
