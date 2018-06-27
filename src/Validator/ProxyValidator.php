<?php

namespace SetBased\Abc\Form\Validator;

use SetBased\Abc\Form\Control\Control;

/**
 * A validator for validating controls that delegates the validation to a callable.
 */
class ProxyValidator implements Validator
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
   * Returns true if the value of the form control meets the conditions of this validator. Returns false otherwise.
   *
   * @param Control $control The form control.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function validate(Control $control): bool
  {
    return ($this->data===null) ? ($this->callable)($control) : ($this->callable)($control, $this->data);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
