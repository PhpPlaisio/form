<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Walker;

use Plaisio\Form\Control\TextControl;
use Plaisio\Form\Walker\LoadWalker;
use SetBased\Exception\FallenException;

/**
 * Form control for testing LoadWalker.
 */
class TestControl extends TextControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The name of the form control that must execute the test.
   *
   * @var string
   */
  public static string $testControlName = '';

  /**
   * The name of the test that must be executed.
   *
   * @var string
   */
  public static string $testName = '';

  /**
   * The test result.
   *
   * @var mixed
   */
  public static mixed $testResult = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function loadSubmittedValuesBase(LoadWalker $walker): void
  {
    if ($this->name===self::$testControlName)
    {
      switch (self::$testName)
      {
        case 'getPath':
          self::$testResult = $walker->getPath();
          break;

        case 'getRootWhitelistValues':
          self::$testResult = unserialize(serialize($walker->getRootWhitelistValues()));
          break;

        case 'getWhitelistValueByPath(/A/AA/AAA)':
          self::$testResult = unserialize(serialize($walker->getWhitelistValueByPath('/A/AA/AAA')));
          break;

        case 'getWhitelistValueByPath(..)':
          self::$testResult = unserialize(serialize($walker->getWhitelistValueByPath('..')));
          break;

        case 'getWhitelistValueByPath(../CA)':
          self::$testResult = unserialize(serialize($walker->getWhitelistValueByPath('../CA')));
          break;

        case 'getWhitelistValueByPath(../../A/AB/ABC)':
          self::$testResult = unserialize(serialize($walker->getWhitelistValueByPath('../../A/AB/ABC')));
          break;

        case 'getWhitelistValueByPath(/Z)':
          self::$testResult = unserialize(serialize($walker->getWhitelistValueByPath('/Z')));
          break;

        default:
          throw new FallenException('test', self::$testName);
      }
    }

    parent::loadSubmittedValuesBase($walker);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
