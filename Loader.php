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

namespace PHPJs;

/**
 * Autoloader Class
 *
 * Very basic autoloader class.
 *
 * @package PHPJs
 * @author Loader
 * @copyright David Mann
 * @version 2011
 * @access public
 */
class Loader
{

  protected static $instance = null;

  /**
   * Constructor
   */
  private function __construct()
  {

  }

  /**
   * Clone
   */
  private function __clone()
  {

  }

  /**
   * Register Autoloader
   *
   * @return void
   */
  public static function register()
  {
    set_include_path(realpath(dirname(__DIR__)) . ';' . get_include_path());
    spl_autoload_register('\PHPJs\Loader::autoload');
  }

  /**
   * Autoload a file
   *
   * Attempt to auto load the requested files
   *
   * @param string $name The name of the class to load
   * @return void
   */
  public static function autoload($name)
  {
    require_once ($name . '.php');
  }
}