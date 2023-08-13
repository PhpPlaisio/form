<?php
declare(strict_types=1);

namespace Plaisio\Form\Walker;

use Plaisio\Form\Cleaner\CompoundCleaner;
use SetBased\Exception\LogicException;

/**
 * Class for walking the form control tree when loading submitted values.
 */
class LoadWalker
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The name of the branch where this walker starts walking.
   *
   * @var string
   */
  private string $branch;

  /**
   * The form controls of which the value has changed.
   *
   * @var array
   */
  private array $changedControls;

  /**
   * The parent walker of this walker.
   *
   * @var LoadWalker|null
   */
  private ?LoadWalker $parent;

  /**
   * The submitted values.
   *
   * @var array
   */
  private array $submittedValues;

  /**
   * The whitelisted submitted values.
   *
   * @var array
   */
  private array $whiteListValues;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param array           $submittedValues The submitted values.
   * @param array           $whiteListValues The white-listed submitted values.
   * @param array           $changedControls The form controls of which the value has changed.
   * @param string          $branch          The name of the branch where this walker starts walking.
   * @param LoadWalker|null $parent          The parent walker of this walker.
   */
  public function __construct(array       $submittedValues,
                              array       &$whiteListValues,
                              array       &$changedControls,
                              string      $branch,
                              ?LoadWalker $parent = null)
  {
    $this->submittedValues = $submittedValues;
    $this->whiteListValues = &$whiteListValues;
    $this->changedControls = &$changedControls;
    $this->branch          = $branch;
    $this->parent          = $parent;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Ascends upto the form control tree.
   *
   * @param string $name The name of the form control.
   *
   * @return array
   */
  public function ascend(string $name): array
  {
    if ($name==='')
    {
      return $this->whiteListValues;
    }

    if (empty($this->changedControls[$name]))
    {
      unset($this->changedControls[$name]);
    }

    return $this->whiteListValues[$name] ?? [];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Cleans the submitted values.
   *
   * @param CompoundCleaner $cleaner The cleaner.
   */
  public function clean(CompoundCleaner $cleaner): void
  {
    $this->submittedValues = $cleaner->clean($this->submittedValues);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Descends into the form control tree.
   *
   * @param string $name      The name of the form control.
   * @param string $submitKey The submit key of the form control.
   *
   * @return $this
   */
  public function descend(string $name, string $submitKey): LoadWalker
  {
    if ($name==='')
    {
      return $this;
    }

    if (!is_array($this->submittedValues[$submitKey] ?? null))
    {
      $this->submittedValues[$submitKey] = [];
    }
    if (!isset($this->whiteListValues[$name]))
    {
      $this->whiteListValues[$name] = [];
    }
    if (!isset($this->changedControls[$name]))
    {
      $this->changedControls[$name] = [];
    }

    return new LoadWalker($this->submittedValues[$submitKey],
                          $this->whiteListValues[$name],
                          $this->changedControls[$name],
                          $name,
                          $this);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the absolute path of this walker.
   *
   * @return string
   */
  public function getPath(): string
  {
    if ($this->parent===null)
    {
      $path = '/'.$this->branch;
    }
    else
    {
      $path = $this->parent->getPath();
      if ($this->branch!=='')
      {
        $path .= (($path==='/') ? '' : '/').$this->branch;
      }
    }

    return $path;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the whitelisted submitted values from the root.
   *
   * @return array
   */
  public function getRootWhitelistValues(): array
  {
    if ($this->parent===null)
    {
      return $this->whiteListValues;
    }

    return $this->parent->getRootWhitelistValues();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the submitted value for the current form control.
   *
   * @param int|string $submitKey The key under which the value is submitted.
   *
   * @return mixed
   */
  public function getSubmittedValue(int|string $submitKey): mixed
  {
    if ($submitKey==='')
    {
      return $this->submittedValues;
    }

    return $this->submittedValues[$submitKey] ?? null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the whitelisted submitted value given a path.
   *
   * @param string $path The path.
   *
   * @return mixed
   */
  public function getWhitelistValueByPath(string $path): mixed
  {
    if (str_starts_with($path, '/'))
    {
      if ($this->parent!==null)
      {
        $value = $this->parent->getWhitelistValueByPath($path);
      }
      else
      {
        $value = $this->getWhitelistValueByPath(substr($path, 1));
      }
    }
    elseif (str_starts_with($path, '../'))
    {
      $value = $this->parent->getWhitelistValueByPath(substr($path, 3));
    }
    elseif ($path==='..')
    {
      $value = $this->parent->getWhitelistValueByPath('.');
    }
    elseif ($path==='.' || $path==='')
    {
      $value = $this->whiteListValues;
    }
    else
    {
      $tmp      = $this->whiteListValues;
      $branches = explode('/', $path);
      $path     = '';
      foreach ($branches as $branch)
      {
        if (!array_key_exists($branch, $tmp))
        {
          $fullPath = $this->getPath().(($path==='') ? '' : '/'.$path);
          throw new LogicException('Branch %s does not exists at path %s.', $branch, $fullPath);
        }
        $tmp  = $tmp[$branch];
        $path = $path.'/'.$branch;
      }
      $value = $tmp;
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the whitelisted value of the current form control.
   *
   * @param string $name
   *
   * @return mixed
   */
  public function getWithListValue(string $name): mixed
  {
    return $this->whiteListValues[$name] ?? null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the current form control as a changed form control.
   *
   * @param int|string $name The name of the current form control.
   */
  public function setChanged(int|string $name): void
  {
    $this->changedControls[$name] = true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the whitelisted value for the current form control.
   *
   * @param int|string $name  The name of the current form control.
   * @param mixed      $value The whitelisted values.
   */
  public function setWithListValue(int|string $name, mixed $value): void
  {
    $this->whiteListValues[$name] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
