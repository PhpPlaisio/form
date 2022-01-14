<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use SetBased\Helper\Cast;

/**
 * Class for form controls of type [input:radio](http://www.w3schools.com/tags/tag_input.asp).
 */
class RadioControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use Mutability;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function htmlControl(RenderWalker $walker): string
  {
    $this->addControlClasses($walker, 'radio');

    $this->attributes['type']    = 'radio';
    $this->attributes['name']    = $this->submitName;
    $this->attributes['checked'] = !empty($this->value);

    $ret = $this->prefix;
    $ret .= $this->htmlPrefixLabel();
    $ret .= Html::htmlNested(['tag'  => 'input',
                              'attr' => $this->attributes]);
    $ret .= $this->htmlPostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [value](http://www.w3schools.com/tags/att_input_value.asp).
   *
   * @param mixed $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrValue($value): self
  {
    $this->attributes['value'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    if ($this->immutable===true)
    {
      if ($walker->getWithListValue($this->name)===null && !empty($this->value))
      {
        $walker->setWithListValue($this->name, $this->attributes['value']);
      }
    }
    else
    {
      $submitKey = $this->submitKey();
      $newValue  = $walker->getSubmittedValue($submitKey) ?? '';

      if (isset($this->attributes['value']) &&
        Cast::toManString($newValue, '')===Cast::toManString($this->attributes['value'], ''))
      {
        if (empty($this->value))
        {
          $walker->setChanged($this->name);
        }
        $this->attributes['checked'] = true;
        $this->value                 = $this->attributes['value'];
        $walker->setWithListValue($this->name, $this->attributes['value']);
      }
      else
      {
        if (!empty($this->value))
        {
          $walker->setChanged($this->name);
        }
        $this->attributes['checked'] = false;
        $this->value                 = null;

        // If the white listed value is not set by a radio button with the same name as this radio button, set the white
        // listed value of this radio button (and other radio buttons with the same name) to null. If another radio button
        // with the same name is checked the white listed value will be overwritten.
        if ($walker->getWithListValue($this->name)===null)
        {
          $walker->setWithListValue($this->name, null);
        }
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
