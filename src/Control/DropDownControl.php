<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Helper\RenderWalker;

/**
 * Class for customizable dropdown select boxes with JavaScript.
 */
class DropDownControl extends SelectControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Class for distinguishing drop down controls from other select controls.
   *
   * @var string
   */
  public static string $cssClass = 'drop-down-control';

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtml(RenderWalker $walker): string
  {
    $this->addClass(self::$cssClass);

    return parent::getHtml($walker);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
