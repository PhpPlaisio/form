<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Functional\Dependant;

use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Walker\LoadWalker;

/**
 * Control for states based on the value control with a country.
 */
class StateControl extends SelectControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    $country       = $walker->getWhitelistValueByPath('../country/abbreviation');
    $this->options = $this->getStates($country);

    parent::loadSubmittedValuesBase($walker);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a list of states/provinces given a country abbreviation.
   *
   * @param string $country The country abbreviation.
   *
   * @return array
   */
  private function getStates(string $country): array
  {
    switch ($country)
    {
      case 'USA':
        return [['key' => 'NY', 'name' => 'New York'],
                ['key' => 'CA', 'name' => 'California']];

      case 'NED':
        return [['key' => 'NH', 'name' => 'Noord-Holland'],
                ['key' => 'ZH', 'name' => 'Zuid-Holland']];

      default:
        return [];
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
