<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Arranger;

use Plaisio\Form\Arranger\ProxyArranger;
use Plaisio\Form\Control\ComplexControl;
use Plaisio\Form\Control\CompoundControl;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\RawForm;
use Plaisio\Form\Test\PlaisioTestCase;
use Plaisio\Helper\RenderWalker;

/**
 * Test cases for class ProxyArranger.
 */
class ProxyArrangerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Arrangers child form control in reverse order.
   *
   * @param RenderWalker    $walker        The object for walking the form control tree.
   * @param CompoundControl $parentControl The parent control of which the child control must be arranged.
   *
   * @return string
   */
  public function reverseArranger(RenderWalker $walker, CompoundControl $parentControl): string
  {
    $children = $parentControl->getControls();
    $children = array_reverse($children);

    $html = '';
    foreach ($children as $control)
    {
      $html .= $control->htmlControl($walker);
    }

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test proxy arranger.
   *
   * @return void
   */
  public function testProxy(): void
  {
    $form = new RawForm();

    $complex = new ComplexControl();
    $complex->addFormControl(new TextControl('first'))
            ->addFormControl(new TextControl('last'));

    $fieldSet = new FieldSet();
    $fieldSet->addFormControl($complex);

    $form->addFieldSet($fieldSet)
         ->execute();

    $walker   = new RenderWalker('test');
    $html     = $complex->htmlControl($walker);
    $expected = '<input class="test-text" type="text" name="first"/><input class="test-text" type="text" name="last"/>';
    self::assertEquals($expected, $html);

    $complex->setArranger(new ProxyArranger([$this, 'reverseArranger']));
    $html     = $complex->htmlControl($walker);
    $expected = '<input class="test-text" type="text" name="last"/><input class="test-text" type="text" name="first"/>';
    self::assertEquals($expected, $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
