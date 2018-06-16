<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * A class for pseudo form controls for generating [div](http://www.w3schools.com/tags/tag_div.asp) elements inside
 * forms.
 */
class DivControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The inner HTML code of this div element.
   *
   * @var string|null
   */
  protected $innerHtml;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function generate()
  {
    $html = $this->prefix;
    $html .= Html::generateElement('div', $this->attributes, $this->innerHtml, true);
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns null.
   */
  public function getSubmittedValue()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner HTML of this div element.
   *
   * @param string|null $htmlSnippet The inner HTML. It is the developer's responsibility that it is valid HTML code.
   */
  public function setInnerHtml(?string $htmlSnippet): void
  {
    $this->innerHtml = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner HTML of this div element.
   *
   * @param string|null $text The inner HTML. Special characters will be converted to HTML entities.
   */
  public function setInnerText(?string $text): void
  {
    $this->innerHtml = Html::txt2Html($text);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase($submittedValues, &$whiteListValues, &$changedInputs)
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $invalidFormControls
   *
   * @return bool
   */
  protected function validateBase(&$invalidFormControls)
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
