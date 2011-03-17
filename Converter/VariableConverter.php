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

class VariableConverter extends Converter
{

  protected $varName;

  protected $includeVar;

  public function __construct($varName, $value, $includeVar = true)
  {
    parent::__construct($value);
    $this->varName = $varName;
    $this->includeVar = (boolean)$includeVar;
  }


  /**
   * ArrayConverter::__toString()
   *
   * @return string The variable in "javascript" form
   */
  public function __toString()
  {
    return sprintf('%s%s = %s;', ($this->includeVar ? 'var ' : ''), $this->varName, $this->value);
  }
}