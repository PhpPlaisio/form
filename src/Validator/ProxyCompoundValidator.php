<?php

namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\CompoundControl;

/**
 * A validator for validating compound controls that delegates the validation to a callable.
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
   * If not null the additional data passed to the callable as second argument.
   *
   * @var mixed|null
   */
  private $data;

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Object constructor.
   *
   * @param callable $callable The callable to which the validation must be delegated.
   * @param mixed    $data     If not null the additional data passed to the callable as second argument.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(callable $callable, $data = null)
  {
    $this->callable = $callable;
    $this->data     = $data;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates a compound control (e.g. a complex control or a form).
   *
   * @param CompoundControl $control The compound control to be validated.
   *
   * @return bool On Successful validation returns true, otherwise false.
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
