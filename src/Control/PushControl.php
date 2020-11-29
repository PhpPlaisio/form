<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Control\Traits\Mutability;
use SetBased\Exception\LogicException;
use SetBased\Helper\Cast;

/**
 * Parent class for form controls submit, reset, and button.
 */
class PushControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElement;
  use Mutability;

  //--------------------------------------------------------------------------------------------------------------------
  /** The type of this button. Valid values are:
   *  <ul>
   *  <li> submit
   *  <li> reset
   *  <li> button
   *  </ul>
   *
   * @var string|null
   */
  protected ?string $buttonType = null;

  /**
   * The name of the method for handling the form when the form submit is triggered by this control.
   *
   * @var string|null
   */
  protected ?string $method = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    return $this->generateInputElement($this->buttonType);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array $values Not used.
   */
  public function mergeValuesBase(array $values): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the name of the method for handling the form when the form submit is triggered by this control.
   *
   * @param null|string $method The name of the method.
   */
  public function setMethod(?string $method): void
  {
    $this->method = $method;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a button is not set by this method.
   *
   * @param array|null $values Not used.
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
    if ($this->immutable!==false)
    {
      $submitKey = $this->submitKey();

      if (isset($submittedValues[$submitKey]) &&
        Cast::toManString($submittedValues[$submitKey], '')===Cast::toManString($this->value, ''))
      {
        // We don't register buttons as a changed input, otherwise every submitted form will always have changed inputs.
        // So, skip the following code.
        // $changedInputs[$this->myName] = $this;

        $whiteListValues[$this->name] = $this->value;
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function searchSubmitHandler(array $submittedValues): ?string
  {
    $submitKey = $this->submitKey();

    if (isset($submittedValues[$submitKey]) &&
      Cast::toManString($submittedValues[$submitKey], '')===Cast::toManString($this->value, ''))
    {
      if ($this->method===null)
      {
        throw new LogicException('Submit handler method not set');
      }

      return $this->method;
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
