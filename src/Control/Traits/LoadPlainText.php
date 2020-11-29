<?php
declare(strict_types=1);

namespace Plaisio\Form\Control\Traits;

use SetBased\Helper\Cast;

/**
 * Trait for form controls that load plain text.
 */
trait LoadPlainText
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function loadSubmittedValuesBase(array $submittedValues,
                                          array &$whiteListValues,
                                          array &$changedInputs): void
  {
    $submitKey = $this->submitKey();
    $newValue  = $submittedValues[$submitKey] ?? null;

    if ($this->cleaner)
    {
      $newValue = $this->cleaner->clean($newValue);
    }

    if (Cast::toManString($this->value, '')!==Cast::toManString($newValue, ''))
    {
      $changedInputs[$this->name] = $this;
      $this->value                = $newValue;
    }

    $whiteListValues[$this->name] = $newValue;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
