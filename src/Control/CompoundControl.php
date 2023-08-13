<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Arranger\Arranger;
use Plaisio\Form\Cleaner\CompoundCleaner;

/**
 * Interface for class for HTML elements holding one or more form control elements.
 */
interface CompoundControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a cleaner to this form control.
   *
   * @param CompoundCleaner $cleaner The cleaner.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function addCleaner(CompoundCleaner $cleaner): self;

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
   * Returns all child form controls of this compound form control.
   *
   * @return Control[]
   *
   * @since 1.0.0
   * @api
   */
  public function getControls(): array;

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
   * Returns the name of this form control.
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
  public function getSubmittedValue(): mixed;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the arranger for arranging the HTML code of the child controls of the compound control.
   *
   * @param Arranger $arranger The new arranger of this compound control.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setArranger(Arranger $arranger): self;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds an error message to the list of error messages for this form control.
   *
   * @param string $message The error message.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setErrorMessage(string $message);

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
