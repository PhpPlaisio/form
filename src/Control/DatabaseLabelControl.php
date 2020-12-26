<?php
declare(strict_types=1);

namespace Plaisio\Form\Control;

use Plaisio\Form\Cleaner\DatabaseLabelCleaner;
use Plaisio\Form\Validator\DatabaseLabelValidator;
use Plaisio\Form\Validator\LengthValidator;
use SetBased\Exception\LogicException;

/**
 * Form control for database labels.
 */
class DatabaseLabelControl extends TextControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name      The name of this form control.
   * @param string      $prefix    The required prefix of the database label without trailing underscore, e.g. CMP_ID.
   * @param int         $maxLength The maximum length of the label.
   */
  public function __construct(?string $name, string $prefix, int $maxLength)
  {
    parent::__construct($name);

    $pattern = '/^[A-Z][0-9A-Z_]*_ID$/';
    if (preg_match($pattern, $prefix)!==1)
    {
      throw new LogicException('Prefix %s does not match pattern %s.', $prefix, $pattern);
    }

    $this->setAttrMaxLength($maxLength);
    $this->setAttrPlaceHolder($prefix);
    $this->addCleaner(new DatabaseLabelCleaner($prefix));
    $this->addValidator(new LengthValidator(0, $maxLength));
    $this->addValidator(new DatabaseLabelValidator($prefix));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
