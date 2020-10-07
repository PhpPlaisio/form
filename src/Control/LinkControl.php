<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Helper\Html;

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
  protected ?string $innerHtml = '';

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $ret = $this->prefix;
    $ret .= Html::generateElement('a', $this->attributes, $this->innerHtml, true);
    $ret .= $this->postfix;

    return $ret;
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
   * Sets the inner HTML of this hyperlink element.
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
   * Set the inner text of this hyperlink element.
   *
   * @param bool|int|float|string|null $text The inner text. Special characters will be converted to HTML entities.
   *
   * @since 1.0.0
   * @api
   */
  public function setInnerText($text): void
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
   * Returns always true.
   *
   * @param array $invalidFormControls Not used.
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
