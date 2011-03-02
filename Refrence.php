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

class Refrence {

  protected $value;

  public function __construct(& $value) {
    $this->_value = $value;
  }

  public function __get($key) {
    return $this->_value->$key;
  }

  public function __set($key, $value) {
    return $this->_value->$key = $this;;
  }

  public function offsetGet($key) {
    return $this->get($key);
  }

  public function offsetSet($key, $value) {
    return $this->set($key, $value);
  }

  public function rewind() {

  }

  public function current() {

  }

  public function key() {

  }

  public function valid() {
  }

  public function __unset($key) {
    unset($this->_value->$key);
    return true;
  }

  public function __isset($key) {
    return isset($this->_value->$key);
  }

  public function serialize() {
    return serialize($this->_value);
  }

  public function unserialize() {
    $this->_value = unserialize($this->_value);
  }
  public function next($key, $value) {
    return $this->set($key, $value);
  }

  public function get() {
    return $this->_value->$key;
  }
}