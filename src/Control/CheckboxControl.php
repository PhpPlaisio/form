<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Helper\Html;

/**
 * Class for form controls of type [input:checkbox](http://www.w3schools.com/tags/tag_input.asp).
 */
class CheckboxControl extends SimpleControl
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
  public function getHtml(): string
  {
    $html = $this->prefix;
    $html .= $this->getHtmlPrefixLabel();
    $html .= Html::generateVoidElement('input', $this->attributes);
    $html .= $this->getHtmlPostfixLabel();
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(array $submittedValues,
                                             array &$whiteListValues,
                                             array &$changedInputs): void
  {
    $submitKey = $this->submitKey();

    if ($this->immutable===true)
    {
      $whiteListValues[$this->name] = $this->value;
    }
    else
    {
      if (empty($this->value)!==empty($submittedValues[$submitKey]))
      {
        $changedInputs[$this->name] = $this;
      }

      if (!empty($submittedValues[$submitKey]))
      {
        $this->value                  = true;
        $whiteListValues[$this->name] = true;
      }
      else
      {
        $this->value                  = false;
        $whiteListValues[$this->name] = false;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  protected function prepare(string $parentSubmitName): void
  {
    parent::prepare($parentSubmitName);

    $this->attributes['type']    = 'checkbox';
    $this->attributes['name']    = $this->submitName;
    $this->attributes['checked'] = !empty($this->value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
