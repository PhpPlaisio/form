<?php

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
  public static function setUpBeforeClass()
  {
    Abc::$request = new CoreRequest();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function setUp()
  {
    parent::setUp();

    $_POST = [];

    $_SERVER['REQUEST_URI'] = '/';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
