<?php

/*
 * PHPJs - Javascript PHP Wrapper
 * 
 * (c) David Mann <ninja@codingninja.com.au>
 *
 * This file is part of the PHPJs library.
 * For the full license. Please see the license file bundled
 * with the source code
 */

namespace PHPJs\Converter;

use PHPJs\Converter;

/**
 * StringConverter
 *
 * @package     PHPJs
 * @subpackage  Converter
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
class StringConverter extends Converter
{

  /**
   * Get the string
   *
   * Return's a javascript string
   *
   * Example
   * $string = new StringConverter('This is my string');
   * echo $string; // Returns (including quotes): "This is my string"
   *
   * @return string The string version of this value
   */
  public function __toString()
  {
    return sprintf('"%s"', $this->escape($this->value));
  }

  /**
   * Escape the value
   *
   * Remove's any slashes that could hurt the output.
   *
   * @param string $value The value to escape
   * @return string The escaped value
   */
  public function escape($value)
  {
    $value = addslashes(stripslashes($value));

    return $value;
  }
}