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

class FloatConverter extends Converter
{
  public function __toString()
  {
    return (float)$this->value;
  }
}