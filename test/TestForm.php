<?php
declare(strict_types=1);

namespace Plaisio\Form\Test;

use Plaisio\Form\RawForm;

/**
 * Class for testing class RawForm.
 */
class TestForm extends RawForm
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes this form.
   */
  public function execute()
  {
    $this->prepare();

    $handler = $this->searchSubmitHandler();
    if ($handler!==null)
    {
      $this->loadSubmittedValues();
      $valid = $this->validate();
      if (!$valid)
      {
        $handler = 'handleEchoForm';
      }
    }
    else
    {
      $handler = 'handleEchoForm';
    }

    return $handler;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the submitted values are valid. Otherwise, returns false.
   *
   * @return bool
   */
  public function isValid(): bool
  {
    return empty($this->invalidControls);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function loadSubmittedValues(): void
  {
    parent::loadSubmittedValues();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function prepare(): void
  {
    parent::prepare();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function validate(): bool
  {
    return parent::validate();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
