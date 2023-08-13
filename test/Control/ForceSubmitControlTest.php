<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;

/**
 * Unit tests for class ForceSubmitControl.
 */
class ForceSubmitControlTest extends PushControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElementTest1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new ForceSubmitControl('hidden-button', true);

    self::assertSame(true, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(?string $name): SimpleControl
  {
    return new ForceSubmitControl($name, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
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
