<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Validator;

use Plaisio\Form\Control\Control;
use Plaisio\Form\Walker\LoadWalker;
use Plaisio\Helper\RenderWalker;

/**
 * Form control for testing validators.
 */
class TestControl extends Control
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The value of this form control.
   *
   * @var mixed
   */
  public $value;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $name  The name of the form control.
   * @param mixed  $value The value of this form control.
   */
  public function __construct(string $name, $value)
  {
    parent::__construct($name);

    $this->value = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function htmlControl(RenderWalker $walker): string
  {
    throw new \LogicException('Not implemented');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function getSetValuesBase(array &$values): void
  {
    throw new \LogicException('Not implemented');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function getSubmittedValue(): mixed
  {
    return $this->value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function mergeValuesBase(array $values): void
  {
    throw new \LogicException('Not implemented');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function setValuesBase(?array $values): void
  {
    throw new \LogicException('Not implemented');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  protected function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    throw new \LogicException('Not implemented');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  protected function validateBase(array &$invalidFormControls): bool
  {
    throw new \LogicException('Not implemented');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
