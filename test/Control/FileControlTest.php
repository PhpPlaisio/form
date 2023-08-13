<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\FileControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\Control\Traits\InputElementTest1;
use Plaisio\Form\Test\PlaisioTestCase;

/**
 * Unit tests for class FileControl.
 */
class FileControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElementTest1;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test form control is hidden.
   */
  public function testIsHidden(): void
  {
    $control = new FileControl('hidden');

    self::assertSame(false, $control->isHidden());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function createControl(string $name): SimpleControl
  {
    return new FileControl($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of form control.
   *
   * @return string
   */
  protected function getControlType(): string
  {
    return 'file';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

