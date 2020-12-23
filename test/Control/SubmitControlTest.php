<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Control\SubmitControl;

/**
 * Unit tests for class SubmitControl.
 */
class SubmitControlTest extends PushControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  public function testAttributes(): void
  {
    $attributes = ['formaction'  => 'setAttrFormAction',
                   'formenctype' => 'setAttrFormEncType',
                   'formmethod'  => 'setAttrFormMethod',
                   'formtarget'  => 'setAttrFormTarget'];

    $input = $this->createControl('button');

    $this->htmlAttributesTest($input, $attributes);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(?string $name): SimpleControl
  {
    return new SubmitControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return submit type for form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'submit';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
