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

use PHPJs\Converter;

/**
 * Convert to an integer
 *
 * @package     PHPJs
 * @subpackage  Converter
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
class IntegerConverter extends Converter
{

  /**
   * Convert to an integer
   *
   * @return
   */
  public function __toString()
  {
    return sprintf("%d", $this->value);
  }
}