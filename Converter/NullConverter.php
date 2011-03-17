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
 * Null Value Converter
 *
 * Represents a "null" value and returns 'undefined'
 *
 * @package     PHPJs
 * @subpackage  Converter
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
class NullConverter extends Converter
{

  /**
   * NullConverter::__toString()
   *
   * @return string The string "undefined"
   */
  public function __toString()
  {
    return 'undefined';
  }
}