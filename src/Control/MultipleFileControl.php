<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * Class for form controls of type [input:file](http://www.w3schools.com/tags/tag_input.asp) that allows multiple files
 * to be uploaded.
 */
class MultipleFileControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this form control.
   *
   * @return string
   */
  public function generate()
  {
    $this->attributes['type']     = 'file';
    $this->attributes['name']     = $this->submitName.'[]';
    $this->attributes['multiple'] = true;

    $ret = $this->prefix;
    $ret .= $this->generatePrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->generatePostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [accept](http://www.w3schools.com/tags/att_input_accept.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrForm($value)
  {
    $this->attributes['accept'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Does nothing. It is not possible the set the value of a file form control.
   *
   * @param mixed $value Not used.
   */
  public function setValue($value)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadSubmittedValuesBase(&$submittedValue, &$whiteListValue, &$changedInputs)
  {
    $submit_name = ($this->obfuscator) ? $this->obfuscator->encode($this->name) : $this->name;

    if (isset($_FILES[$submit_name]['name']))
    {
      $changedInputs[$this->name]  = $this;
      $whiteListValue[$this->name] = [];
      $this->value                 = [];

      foreach ($_FILES[$submit_name]['name'] as $i => $dummy)
      {
        if ($_FILES[$submit_name]['error'][$i]===UPLOAD_ERR_OK)
        {
          $tmp = ['name'     => $_FILES[$submit_name]['name'][$i],
                  'type'     => $_FILES[$submit_name]['type'][$i],
                  'tmp_name' => $_FILES[$submit_name]['tmp_name'][$i],
                  'size'     => $_FILES[$submit_name]['size'][$i]];

          $whiteListValue[$this->name][] = $tmp;
          $this->value[]                 = $tmp;
        }
      }
    }

    if (empty($this->value))
    {
      // Either no files have been uploaded or all uploaded files have errors.
      unset($changedInputs[$this->name]);
      $this->value                 = null;
      $whiteListValue[$this->name] = null;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
