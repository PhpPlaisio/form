<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Cleaner\IntegerCleaner;
use Plaisio\Form\Control\Traits\InputElement;
use Plaisio\Form\Control\Traits\LoadPlainText;
use Plaisio\Form\Validator\IntegerValidator;
use Plaisio\Form\Walker\PrepareWalker;
use Plaisio\Form\Walker\RenderWalker;
use SetBased\Helper\Cast;

/**
 * Class for form controls of type [input:number](http://www.w3schools.com/tags/tag_input.asp) with step is an integer.
 * The submitted value of this form control is null or an integer.
 */
class IntegerControl extends SimpleControl
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
  public function __construct(?string $name)
  {
    parent::__construct($name);

    $this->addCleaner(IntegerCleaner::get());
    $this->addValidator(new IntegerValidator());
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
    $this->addControlClasses($walker, 'integer');

    return $this->generateInputElement('number');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [max](http://www.w3schools.com/tags/att_input_max.asp).
   *
   * @param string|int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrMax($value): self
  {
    $this->attributes['max'] = Cast::toOptInt($value);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [min](http://www.w3schools.com/tags/att_input_min.asp).
   *
   * @param string|int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrMin($value): self
  {
    $this->attributes['min'] = Cast::toOptInt($value);

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

    foreach ($this->validators as $validator)
    {
      if (is_a($validator, IntegerValidator::class))
      {
        $validator->setMinValue($this->getAttribute('min'));
        $validator->setMaxValue($this->getAttribute('max'));
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
