<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\HiddenSubmitControl;
use Plaisio\Form\Control\SimpleControl;

/**
 * Unit tests for class HiddenSubmitControl.
 */
class HiddenSubmitControlTest extends PushControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new HiddenSubmitControl('hidden-button');

    self::assertSame(true, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getControl(string $name): SimpleControl
  {
    return new HiddenSubmitControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns input type for form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'hidden';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
