<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;

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
  public function htmlControl(RenderWalker $walker): string
  {
    $this->addControlClasses($walker, 'checkbox');

    $this->attributes['type']    = 'checkbox';
    $this->attributes['name']    = $this->submitName;
    $this->attributes['checked'] = !empty($this->value);

    $html = $this->htmlPrefixLabel();
    $html .= Html::htmlNested(['tag' => 'input', 'attr' => $this->attributes]);
    $html .= $this->htmlPostfixLabel();

    return $html;
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
      $newValue  = !empty($walker->getSubmittedValue($submitKey));
      if (empty($this->value)===$newValue)
      {
        $walker->setChanged($this->name);
      }

      $this->value = $newValue;
    }

    $walker->setWithListValue($this->name, $this->value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
