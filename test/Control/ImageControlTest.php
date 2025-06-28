<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\ImageControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Unit tests for class ImageControl.
 */
class ImageControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElementTest1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new ImageControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of the form control to be tested.
   *
   * @param string $name the name of the form control.
   *
   * @return SimpleControl
   */
  protected function createControl(string $name): SimpleControl
  {
    return new ImageControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'image';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

