<?php

/*
 * PHPJs - ExtJS PHP Wrapper
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
 * Literal Value Converter
 *
 * Return's the string value of the value passed, does not attempt to wrap it
 * or prefix it. Used for functions, variables, etc, etc
 *
 * @package     PHPJs
 * @subpackage  Converter
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
class LiteralConverter extends Converter
{

  /**
   * LiteralConverter::__toString()
   *
   * @return
   */
  public function __toString()
  {
    return (string )$this->value;
  }
}