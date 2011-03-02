<?php

/*
 * ExtPHP - ExtJS PHP Wrapper
 * 
 * (c) David Mann <ninja@codingninja.com.au>
 *
 * This file is part of the ExtPHP library.
 * For the full license. Please see the license file bundled
 * with the source code
 */

namespace PHPJs\Converter;

use PHPJs\Component;
use PHPJs\Converter;
use PHPJs\Converter\LiteralConverter;

/**
 * Array Converter
 *
 * Convert's a PHP array into the javascript representation. It wil also
 * recurse through the values of the array and make sure that they will also
 * be converted to the proper {@link Converter} class.
 *
 * @package     PHPJs
 * @subpackage  Converter
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
class ArrayConverter extends Converter
{
  protected $dependencies = array();

  /**
   * ArrayConverter::__construct()
   *
   * @param mixed $value
   * @return
   */
  public function __construct(array $value)
  {
    $values = array();
    foreach ($value as $key => $value)
    {
      if ($value instanceof Component)
      {
        $values[$key] = new LiteralConverter($value->getUuid());
        $this->dependencies[] = $value;
      } elseif ($value instanceof Converter)
      {
        $values[$key] = $value;
      } else
      {
        $values[$key] = self::getConverter($value);
      }
    }

    $this->value = $values;
  }

  /**
   * ArrayConverter::getDependencies()
   *
   * @return
   */
  public function getDependencies()
  {
    return $this->dependencies;
  }

  /**
   * ArrayConverter::__toString()
   *
   * @return
   */
  public function __toString()
  {
    return '[' . implode(', ', array_values($this->value)) . ']';
  }
}