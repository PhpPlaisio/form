<?php
declare(strict_types=1);

namespace Plaisio\Form\Control\Traits;

use Plaisio\Form\Control\ComplexControl;
use Plaisio\Helper\Html;

/**
 * Trait for form controls that are [input](https://www.w3schools.com/tags/tag_input.asp) elements.
 */
trait InputElement
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @param string $type The value of the type attribute of the input element.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  protected function generateInputElement(string $type): string
  {
    if ($this->error)
    {
      $this->addClass(ComplexControl::$isErrorClass);
    }
    $this->attributes['type'] = $type;
    $this->attributes['name'] = $this->submitName;

    if ($this->formatter) $this->attributes['value'] = $this->formatter->format($this->value);
    else                  $this->attributes['value'] = $this->value;

    $ret = $this->prefix;
    $ret .= $this->getHtmlPrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->getHtmlPostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
