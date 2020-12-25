<?php
declare(strict_types=1);

namespace Plaisio\Form\Control\Traits;

use Plaisio\Form\Walker\LoadWalker;
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
  public function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    if ($this->immutable!==true)
    {
      $submitKey = $this->submitKey();
      $newValue  = $this->clean($walker->getSubmittedValue($submitKey));

      if (Cast::toManString($this->value, '')!==Cast::toManString($newValue, ''))
      {
        $walker->setChanged($this->name);
      }
      $this->value = $newValue;
    }

    $walker->setWithListValue($this->name, $this->value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
