<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Control\Traits\Mutability;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Helper\RenderWalker;
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
   * @var string
   */
  protected string $buttonType = '';

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
  public function htmlControl(RenderWalker $walker): string
  {
    $this->addControlClasses($walker, $this->buttonType);

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
   *
   * @return $this
   */
  public function setMethod(?string $method): self
  {
    $this->method = $method;

    return $this;
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
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    if ($this->immutable!==false)
    {
      $submitKey = $this->submitKey();
      $newValue  = $walker->getSubmittedValue($submitKey);
      if ($newValue!==null && Cast::toManString($newValue, '')===Cast::toManString($this->value, ''))
      {
        // We don't register buttons as a changed input, otherwise every submitted form will always have changed inputs.
        $walker->setWithListValue($this->name, $this->value);
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
