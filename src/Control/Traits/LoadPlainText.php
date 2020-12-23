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
  use Mutability;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function loadSubmittedValuesBase(array $submittedValues,
                                          array &$whiteListValues,
                                          array &$changedInputs): void
  {
    if ($this->immutable===true)
    {
      $whiteListValues[$this->name] = $this->value;
    }
    else
    {
      $submitKey = $this->submitKey();
      $newValue  = $this->clean($submittedValues[$submitKey] ?? null);

      if (Cast::toManString($this->value, '')!==Cast::toManString($newValue, ''))
      {
        $changedInputs[$this->name] = $this;
        $this->value                = $newValue;
      }

      $whiteListValues[$this->name] = $newValue;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
