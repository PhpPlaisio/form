<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Control\Traits\LoadPlainText;

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
   * @inheritdoc
   *
   * @since 1.0.0
   * @api
   */
  public function getHtml(): string
  {
    return $this->generateInputElement('date');
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
    foreach ($this->cleaners as $cleaner)
    {
      if (method_exists($cleaner, 'setOpenDate'))
      {
        $cleaner->setOpenDate($openDate);
      }
    }
    if (method_exists($this->formatter, 'setOpenDate'))
    {
      $this->formatter->setOpenDate($openDate);
    }

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
