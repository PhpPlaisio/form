<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Cleaner\CompoundCleaner;

/**
 * Interface for object that getHtml HTML elements holding one or more form control elements.
 */
interface CompoundControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control with by name. If more than one form control with the same name exists the first
   * found form control is returned. If no form control is found null is returned.
   *
   * @param string $name The name of the searched form control.
   *
   * @return Control|null
   *
   * @since 1.0.0
   * @api
   */
  public function findFormControlByName(string $name): ?Control;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control by path. If more than one form control with same path exists the first found form
   * control is returned. If not form control is found null is returned.
   *
   * @param string $path The path of the searched form control.
   *
   * @return Control|null
   *
   * @since 1.0.0
   * @api
   */
  public function findFormControlByPath(string $path): ?Control;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control with by name. If more than one form control with the same name exists the first found
   * form control is returned. If no form control is found an exception is thrown.
   *
   * @param string $name The name of the searched form control.
   *
   * @return Control
   *
   * @since 1.0.0
   * @api
   */
  public function getFormControlByName(string $name): Control;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Searches for a form control by path. If more than one form control with the same path exists the first found
   * form control is returned. If no form control is found an exception is thrown.
   *
   * @param string $path The path of the searched form control.
   *
   * @return Control
   *
   * @since 1.0.0
   * @api
   */
  public function getFormControlByPath(string $path): Control;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the name of this form control
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getName(): string;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value of this form control.
   *
   * @return array
   *
   * @since 1.0.0
   * @api
   */
  public function getSubmittedValue(): array;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the cleaner for this form control.
   *
   * @param CompoundCleaner|null $cleaner The cleaner.
   *
   * @since 1.0.0
   * @api
   */
  public function setCleaner(?CompoundCleaner $cleaner): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an error message to the list of error messages for this form control.
   *
   * @param string $message The error message.
   *
   * @return void
   *
   * @since 1.0.0
   * @api
   */
  public function setErrorMessage(string $message): void;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
