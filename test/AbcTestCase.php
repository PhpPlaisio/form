<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Abc;
use SetBased\Abc\Request\CoreRequest;

/**
 * Parent class for all unit tests.
 */
class AbcTestCase extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public static function setUpBeforeClass(): void
  {
    Abc::$request = new CoreRequest();
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
