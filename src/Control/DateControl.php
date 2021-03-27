<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Cleaner\AmbiguityCleaner;
use Plaisio\Form\Cleaner\DateCleaner;
use Plaisio\Form\Cleaner\PruneWhitespaceCleaner;
use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Control\Traits\LoadPlainText;
use Plaisio\Form\Formatter\DateFormatter;
use Plaisio\Form\Validator\DateValidator;
use Plaisio\Form\Walker\PrepareWalker;
use Plaisio\Helper\RenderWalker;

/**
 * Class for form controls of type [input:date](http://www.w3schools.com/tags/tag_input.asp).
 */
class DateControl extends SimpleControl
{
  //--------------------------------------------------------------------------------------------------------------------
  use InputElement;
  use LoadPlainText;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The open date of this form control.
   *
   * @var string|null
   */
  private ?string $openDate = null;

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

    $this->addCleaner(AmbiguityCleaner::get())
         ->addCleaner(PruneWhitespaceCleaner::get())
         ->addCleaner(new DateCleaner())
         ->setFormatter(new DateFormatter())
         ->addValidator(new DateValidator());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(RenderWalker $walker): string
  {
    $this->addControlClasses($walker, 'date');

    return $this->generateInputElement('date');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the open date. An empty submitted value will be replaced with the open date and an open date will be shown as
   * an empty field.
   *
   * @param string|null $openDate The open date in YYYY-MM-DD format.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setOpenDate(?string $openDate): self
  {
    $this->openDate = $openDate;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form control for HTML code generation or loading submitted values.
   *
   * @param PrepareWalker $walker The object for walking the form control tree.
   *
   * @since 1.0.0
   * @api
   */
  protected function prepare(PrepareWalker $walker): void
  {
    parent::prepare($walker);

    foreach ($this->cleaners as $cleaner)
    {
      if (method_exists($cleaner, 'setOpenDate'))
      {
        $cleaner->setOpenDate($this->openDate);
      }
    }
    if ($this->formatter!==null && method_exists($this->formatter, 'setOpenDate'))
    {
      $this->formatter->setOpenDate($this->openDate);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
