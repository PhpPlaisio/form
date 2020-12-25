<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Walker;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Control\ComplexControl;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\ForceSubmitControl;
use Plaisio\Form\RawForm;

/**
 * Tests for class LoadWalker.
 */
class LoadWalkerTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getPath().
   */
  public function testGetPath1(): void
  {
    TestControl::$testName        = 'getPath';
    TestControl::$testControlName = 'CCC';

    $form = $this->createForm();
    $form->execute();

    self::assertSame('/C/CC', TestControl::$testResult);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getPath().
   */
  public function testGetPath2(): void
  {
    TestControl::$testName        = 'getPath';
    TestControl::$testControlName = 'ABC';

    $form = $this->createForm();
    $form->execute();

    self::assertSame('/A/AB', TestControl::$testResult);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getRootWhitelistValues().
   */
  public function testGetRootWhitelistValues1(): void
  {
    TestControl::$testName        = 'getRootWhitelistValues';
    TestControl::$testControlName = 'AAA';

    $form = $this->createForm();
    $form->execute();

    self::assertSame(['A' => ['AA' => []]], TestControl::$testResult);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getRootWhitelistValues().
   */
  public function testGetRootWhitelistValues2(): void
  {
    TestControl::$testName        = 'getRootWhitelistValues';
    TestControl::$testControlName = 'ABB';

    $form = $this->createForm();
    $form->execute();

    self::assertSame(['A' => ['AA' => ['AAA' => 'aaa',
                                       'AAB' => 'aab',
                                       'AAC' => 'aac'],
                              'AB' => ['ABA' => 'aba']]],
                     TestControl::$testResult);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getWhitelistValueByPath().
   */
  public function testGetWhitelistValueByPath1(): void
  {
    TestControl::$testName        = 'getWhitelistValueByPath(/A/AA/AAA)';
    TestControl::$testControlName = 'CCC';

    $form = $this->createForm();
    $form->execute();

    self::assertSame('aaa', TestControl::$testResult);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getWhitelistValueByPath().
   */
  public function testGetWhitelistValueByPath2(): void
  {
    TestControl::$testName        = 'getWhitelistValueByPath(..)';
    TestControl::$testControlName = 'BBB';

    $form = $this->createForm();
    $form->execute();

    self::assertSame(['BA' => ['BAA' => 'baa',
                               'BAB' => 'bab',
                               'BAC' => 'bac'],
                      'BB' => ['BBA' => 'bba']],
                     TestControl::$testResult);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getWhitelistValueByPath().
   */
  public function testGetWhitelistValueByPath3(): void
  {
    TestControl::$testName        = 'getWhitelistValueByPath(../CA)';
    TestControl::$testControlName = 'CCC';

    $form = $this->createForm();
    $form->execute();

    self::assertSame(['CAA' => 'caa',
                      'CAB' => 'cab',
                      'CAC' => 'cac'],
                     TestControl::$testResult);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getWhitelistValueByPath().
   */
  public function testGetWhitelistValueByPath4(): void
  {
    TestControl::$testName        = 'getWhitelistValueByPath(../../A/AB/ABC)';
    TestControl::$testControlName = 'CCC';

    $form = $this->createForm();
    $form->execute();

    self::assertSame('abc', TestControl::$testResult);
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getWhitelistValueByPath().
   */
  public function testGetWhitelistValueByPath5(): void
  {
    $this->expectException(\LogicException::class);
    $this->expectErrorMessage('Branch Z does not exists at path /.');
    TestControl::$testName        = 'getWhitelistValueByPath(/Z)';
    TestControl::$testControlName = 'CCC';

    $form = $this->createForm();
    $form->execute();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates a form for testing LoadWalker.
   *
   * @return RawForm
   */
  private function createForm(): RawForm
  {
    $_POST = [];
    foreach (['A', 'B', 'C'] as $name1)
    {
      foreach (['A', 'B', 'C'] as $name2)
      {
        foreach (['A', 'B', 'C'] as $name3)
        {
          $_POST[$name1][$name1.$name2][$name1.$name2.$name3] = strtolower($name1.$name2.$name3);
        }
      }
    }

    $form = new RawForm();

    foreach (['A', 'B', 'C'] as $name1)
    {
      $fieldset = new FieldSet($name1);

      foreach (['A', 'B', 'C'] as $name2)
      {
        $complex = new ComplexControl($name1.$name2);

        foreach (['A', 'B', 'C'] as $name3)
        {
          $input = new TestControl($name1.$name2.$name3);
          $complex->addFormControl($input);
        }

        $fieldset->addFormControl($complex);
      }

      $form->addFieldSet($fieldset);
    }

    $submit = new ForceSubmitControl('submit', true);
    $submit->setMethod('handleForm');
    $fieldset->addFormControl($submit);

    return $form;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
