<?php

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * A class for pseudo form controls for generating [hyperlink](http://www.w3schools.com/tags/tag_a.asp) elements inside
 * forms.
 */
class LinkControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The inner HTML code of this hyperlink element.
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
    $ret = $this->prefix;
    $ret .= Html::generateElement('a', $this->attributes, $this->innerHtml, true);
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns null;
   */
  public function getSubmittedValue()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the inner HTML of this hyperlink element.
   *
   * @param string|null $htmlSnippet The inner HTML. It is the developer's responsibility that it is valid HTML code.
   */
  public function setInnerHtml(?string $htmlSnippet): void
  {
    $this->innerHtml = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner HTML of this hyperlink element.
   *
   * @param string|null $text The inner text. Special characters will be converted to HTML entities.
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
   * Returns always true.
   *
   * @param array $invalidFormControls Not used.
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
