<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use Plaisio\Form\Control\TextControl;
use Plaisio\Form\Control\WrapperControl;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Unit tests for class WrapperControl.
 */
class WrapperControlTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method getTag().
   */
  public function testGetTag(): void
  {
    $wrapper = new WrapperControl();
    self::assertEquals('div', $wrapper->getTag());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method htmlControl().
   */
  public function testSetClass(): void
  {
    $inner = new TextControl('inner');

    $wrapper = new WrapperControl();
    $wrapper->addClass('wrapper')
            ->addFormControl($inner);

    $html = $wrapper->getAutonomousHtml('module');
    self::assertEquals('<div class="wrapper"><input class="module-text" type="text" name="inner"/></div>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method setTag().
   */
  public function testSetTag(): void
  {
    $wrapper = new WrapperControl();
    $wrapper->setTag('span');

    $html = $wrapper->getAutonomousHtml('module');
    self::assertEquals('<span></span>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
