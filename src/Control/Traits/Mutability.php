<?php
declare(strict_types=1);

namespace Plaisio\Form\Control\Traits;

/**
 * Trait for mutability of form controls.
 */
trait Mutability
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Whether this form control is immutable. The value of an immutable form control will not change on receiving
   * submitted values.
   *
   * @var bool|null
   */
  protected ?bool $immutable = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether this form control is immutable.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function isImmutable(): bool
  {
    return $this->immutable ?? false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether this form control is mutable.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function isMutable(): bool
  {
    return !($this->immutable ?? false);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets whether this form control is immutable.
   *
   * @param bool|null $immutable Whether this form control is immutable.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setImmutable(?bool $immutable): self
  {
    $this->immutable = $immutable;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets whether this form control is mutable.
   *
   * @param bool|null $mutable Whether this form control is mutable.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setMutable(?bool $mutable): self
  {
    $this->immutable = ($mutable===null) ? null : !$mutable;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
