<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Plaisio;

use PHPUnit\Framework\TestCase;
use Plaisio\PlaisioKernel;

/**
 * Parent class for all unit tests.
 */
class PlaisioTestCase extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Our concrete instance of Nub.
   *
   * @var PlaisioKernel
   */
  private PlaisioKernel $kernel;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Base test for testing setting attributes.
   *
   * @param Object $object     The HTML child class.
   * @param array  $attributes The attribute names.
   */
  protected function htmlAttributesTest(object $object, array $attributes): void
  {
    foreach ($attributes as $attribute => $method)
    {
      $object->$method('some value');

      self::assertSame('some value', $object->getAttribute($attribute));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  protected function setUp(): void
  {
    $_POST = [];
    $_SERVER['REQUEST_URI'] = '/';

    $this->kernel = new TestKernel();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
