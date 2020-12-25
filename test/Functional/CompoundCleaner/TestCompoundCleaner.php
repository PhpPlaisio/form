<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Functional\CompoundCleaner;

use Plaisio\Form\Cleaner\CompoundCleaner;

/**
 * Test cleaner.
 */
class TestCompoundCleaner implements CompoundCleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function clean(array $values): array
  {
    if (isset($values['A/B']))
    {
      [$values['A'], $values['B']] = explode('/', $values['A/B']);
    }

    unset($values['A/B']);

    return $values;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
