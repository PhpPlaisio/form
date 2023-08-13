<?php
declare(strict_types=1);

namespace Plaisio\Form\Validator;

use Plaisio\Form\Control\CompoundControl;

/**
 * A validator for validating compound form controls that delegates the validation to a callable.
 */
class ProxyCompoundValidator implements CompoundValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The callable to which the validation must be delegated.
   *
   * @var callable
   */
  private $callable;

  /**
   * The additional data passed to the callable as second argument.
   *
   * @var mixed
   */
  private mixed $data;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param callable   $callable The callable to which the validation must be delegated.
   * @param mixed|null $data     The additional data passed to the callable as second argument.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(callable $callable, mixed $data = null)
  {
    $this->callable = $callable;
    $this->data     = $data;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether the values of a compound form control meets the conditions of this validator.
   *
   * @param CompoundControl $control The compound form control to be validated.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function validate(CompoundControl $control): bool
  {
    return ($this->data===null) ? ($this->callable)($control) : ($this->callable)($control, $this->data);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
