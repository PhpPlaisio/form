<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Helper\Html;

/**
 * Class for form controls of type [input:file](http://www.w3schools.com/tags/tag_input.asp) that allows multiple files
 * to be uploaded.
 */
class MultipleFileControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use Mutability;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $this->attributes['type']     = 'file';
    $this->attributes['name']     = $this->submitName.'[]';
    $this->attributes['multiple'] = true;

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
      $submitKey = $this->submitKey();
      if (isset($_FILES[$submitKey]['name']))
      {
        $this->value = [];

        foreach ($_FILES[$submitKey]['name'] as $i => $dummy)
        {
          if ($_FILES[$submitKey]['error'][$i]===UPLOAD_ERR_OK)
          {
            $tmp = ['name'     => $_FILES[$submitKey]['name'][$i],
                    'type'     => $_FILES[$submitKey]['type'][$i],
                    'tmp_name' => $_FILES[$submitKey]['tmp_name'][$i],
                    'size'     => $_FILES[$submitKey]['size'][$i]];

            $this->value[] = $tmp;
          }
        }
      }

      if (empty($this->value))
      {
        // Either no files have been uploaded or all uploaded files have errors.
        $this->value = null;
      }
      else
      {
        $walker->setChanged($this->name);
      }
    }

    $walker->setWithListValue($this->name, $this->value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
