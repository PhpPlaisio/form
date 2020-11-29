<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Cleaner\DateCleaner;
use Plaisio\Form\Formatter\DateFormatter;
use Plaisio\Form\Validator\DateValidator;

/**
 * Class for form controls with jQuery UI datepicker.
 */
class DateControl extends TextControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    $this->setAttrSize(10)
         ->setAttrMaxLength(10)
         ->addClass('datepicker')
         ->setCleaner(new DateCleaner('d-m-Y', '-', '/. :\\'))
         ->setFormatter(new DateFormatter('d-m-Y'))
         ->addValidator(new DateValidator());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the open date. An empty submitted value will be replaced with the open date and an open date will be shown as
   * an empty field.
   *
   * @param string $openDate The open date in YYYY-MM-DD format.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setOpenDate(string $openDate): self
  {
    $this->cleaner->setOpenDate($openDate);
    $this->formatter->setOpenDate($openDate);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
