<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Walker\LoadWalker;
use SetBased\Exception\LogicException;

/**
 * Class for mimicking a submit button that has been submitted.
 */
class ForceSubmitControl extends PushControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Whether this submit button has been submitted.
   *
   * @var bool
   */
  private bool $force;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name  The name of the form control.
   * @param bool        $force If the true this submit button has been submitted.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?string $name, bool $force)
  {
    parent::__construct($name);

    $this->buttonType = 'hidden';
    $this->force      = $force;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true.
   *
   * @since 1.0.0
   * @api
   */
  public function isHidden(): bool
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true.
   *
   * @since 1.0.0
   * @api
   */
  public function isSubmitTrigger(): bool
  {
    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    if ($this->force)
    {
      // We don't register buttons as a changed input, otherwise every submitted form will always have changed inputs.
      // So, skip the following code.
      // $walker->setChanged($this->name);

      $walker->setWithListValue($this->name, $this->value);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a hidden button is not set by this method.
   *
   * @param array $values Not used.
   */
  public function mergeValuesBase(array $values): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Has no effect. The value of a hidden button is not set by this method.
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
  protected function searchSubmitHandler(array $submittedValues): ?string
  {
    if ($this->force)
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
