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

namespace ExtPHP\Converter;

use ExtPHP\Converter;

/**
 * MethodCallConverter
 *
 * @package     ExtPHP
 * @subpackage  Converter
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
class MethodCallConverter extends Converter
{

  protected $object;

  protected $method;

  protected $arguments;

  public function __construct(Component $object, $method) {
    $this->setObject($object)
         ->setMethod($method)
         ->setArguments(array_slice(func_get_args(), 2));
  }

  public function setObject(Component $object) {
    $this->object = $object;

    return $this;
  }

  public function setMethod($method) {
    $this->method = $method;

    return $this;
  }

  public function setArguments($arguments) {
    $this->arguments = $arguments;
    return $this;
  }

  public function getObject() {
    return $this->object;
  }

  public function getMethod() {
    return $this->method;
  }

  public function getArguments() {
    return $this->arguments;
  }

  public function compileArguments() {
    return implode(', ', $this->arguments);
  }

  /**
   * Get the method call
   *
   * Return's a javascript method call
   *
   * Example
   * $string = new MethodCallConverter($myComponent, 'SomeMethod', array('key' => 'value'), true);
   * echo $string; // Returns (including quotes): myComponent.SomeMethod({'key''': 'value'}, true);
   *
   * @return string The string version of this value
   */
  public function __toString()
  {
    return sprintf('%s.%s(%s)', $this->getObject()->getUuid(), $this->getMethod(), $this->compileArguments());
  }
}
