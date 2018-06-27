<?php

namespace SetBased\Abc\Form\Control;

/**
 * Class for pseudo form controls for form controls of which the value is constant.
 */
class ConstantControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an empty string.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function generate(): string
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * A constant control must never be shown in a table.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtmlTableCell(): string
  {
    return '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * Note:
   * Always sets the white listed value to the value of this constant form control.
   * Never uses $whiteListValue and never sets the $changedInputs.
   */
  protected function loadSubmittedValuesBase(array $submittedValues,
                                             array &$whiteListValues,
                                             array &$changedInputs): void
  {
    $whiteListValues[$this->name] = $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
