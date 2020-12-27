<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\PasswordControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Test\Control\Traits\ImmutableTest;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\Control\Traits\InputElementTest2;

/**
 * Unit tests for class PasswordControl.
 */
class PasswordControlTest extends SimpleControlTest
{
  //--------------------------------------------------------------------------------------------------------------------
  use ImmutableTest;
  use InputElementTest1;
  use InputElementTest2;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new PasswordControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(string $name): SimpleControl
  {
    return new PasswordControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'password';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
