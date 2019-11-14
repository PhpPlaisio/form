<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Control;

use Plaisio\Helper\Html;
use SetBased\Abc\Form\Cleaner\PruneWhitespaceCleaner;
use SetBased\Helper\Cast;

/**
 * Class for form controls of type [input:text](http://www.w3schools.com/tags/tag_input.asp).
 */
class TextControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    // By default whitespace is pruned from text form controls.
    $this->cleaner = PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    $this->attributes['type'] = 'text';
    $this->attributes['name'] = $this->submitName;

    if ($this->formatter) $this->attributes['value'] = $this->formatter->format($this->value);
    else                  $this->attributes['value'] = $this->value;

    $ret = $this->prefix;
    $ret .= $this->getHtmlPrefixLabel();
    $ret .= Html::generateVoidElement('input', $this->attributes);
    $ret .= $this->getHtmlPostfixLabel();
    $ret .= $this->postfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(array $submittedValues,
                                             array &$whiteListValues,
                                             array &$changedInputs): void
  {
    $submitKey = $this->submitKey();

    // Get the submitted value.
    $newValue = $submittedValues[$submitKey] ?? null;

    // Clean the submitted value, if we have a cleaner.
    if ($this->cleaner) $newValue = $this->cleaner->clean($newValue);

    if (Cast::toManString($this->value, '')!==Cast::toManString($newValue, ''))
    {
      $changedInputs[$this->name] = $this;
      $this->value                = $newValue;
    }

    // The user can enter any text in a input:text box. So, any value is white listed.
    $whiteListValues[$this->name] = $newValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
