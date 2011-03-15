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

namespace PHPJs;

use \PHPJs\Renderable;
use \PHPJs\Refrence;

class CustomFunction implements Renderable {
  public function __construct($name = null, $namespace = null) {
    if($name !== null) {
      $this->_namespace = $namespace;
      $this->_name = $name;
    }
  }

  public function __call($method, $arguments) {

  }
}