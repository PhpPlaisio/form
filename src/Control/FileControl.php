<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Form\Walker\RenderWalker;
use Plaisio\Helper\Html;

/**
 * Class for form controls of type [input:file](http://www.w3schools.com/tags/tag_input.asp) that allows a single file
 * to be uploaded.
 */
class FileControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use Mutability;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @param RenderWalker $walker The object for walking the form control tree.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(RenderWalker $walker): string
  {
    $this->addControlClasses($walker, 'file');

    $this->attributes['type'] = 'file';
    $this->attributes['name'] = $this->submitName;

    $ret = $this->prefix;
    $ret .= $this->getHtmlPrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->getHtmlPostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [accept](http://www.w3schools.com/tags/att_input_accept.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrAccept(?string $value): self
  {
    $this->attributes['accept'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Does nothing. It is not possible the set the value of a file form control.
   *
   * @param mixed $value Not used.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setValue($value): self
  {
    // Nothing to do.

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    if ($this->immutable!==true)
    {
      $submitName = $this->submitKey();

      if (isset($_FILES[$submitName]['error']) && $_FILES[$submitName]['error']===0)
      {
        $walker->setChanged($this->name);
        $this->value = $_FILES[$submitName];
      }
      else
      {
        $this->value = null;
      }
    }

    $walker->setWithListValue($this->name, $this->value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
