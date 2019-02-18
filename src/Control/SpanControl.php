<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

/**
 * A class for pseudo form controls for generating [span](http://www.w3schools.com/tags/tag_span.asp) elements inside
 * forms.
 */
class SpanControl extends Control
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
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $html = $this->prefix;
    $html .= Html::generateElement('span', $this->attributes, $this->innerHtml, true);
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns null.
   *
   * @since 1.0.0
   * @api
   */
  public function getSubmittedValue()
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner HTML of this span element.
   *
   * @param string|null $htmlSnippet The inner HTML. It is the developer's responsibility that it is valid HTML code.
   *
   * @since 1.0.0
   * @api
   */
  public function setInnerHtml(?string $htmlSnippet): void
  {
    $this->innerHtml = $htmlSnippet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the inner HTML of this span element.
   *
   * @param string $text The inner text. Special characters will be converted to HTML entities.
   *
   * @since 1.0.0
   * @api
   */
  public function setInnerText(?string $text): void
  {
    $this->innerHtml = Html::txt2Html($text);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function setValuesBase(?array $values): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(array $submittedValues,
                                             array &$whiteListValues,
                                             array &$changedInputs): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param array $invalidFormControls
   *
   * @return bool
   */
  protected function validateBase(array &$invalidFormControls): bool
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
