<?php
declare(strict_types=1);

namespace Plaisio\Form\Test;

use PHPUnit\Framework\TestCase;
use Plaisio\Kernel\Nub;
use Plaisio\Request\CoreRequest;

/**
 * Parent class for all unit tests.
 */
class PlaisioTestCase extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public static function setUpBeforeClass(): void
  {
    Nub::$request = new CoreRequest();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Base test for testing setting attributes.
   *
   * @param Object $object     The HTML child class.
   * @param array  $attributes The attribute names.
   */
  protected function htmlAttributesTest($object, array $attributes): void
  {
    foreach ($attributes as $attribute => $method)
    {
      $object->$method('some value');

      self::assertSame('some value', $object->getAttribute($attribute));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function setUp(): void
  {
    parent::setUp();

    $_POST = [];

    $_SERVER['REQUEST_URI'] = '/';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
