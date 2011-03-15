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

use \PHPJs\Converter\ArrayConverter;
use \PHPJs\Converter\LiteralConverter;
use \PHPJs\Converter\VariableConverter;
use \PHPJs\Converter\StringConverter;
use \PHPJs\Converter\FloatConverter;
use \PHPJs\Converter\IntegerConverter;
use \PHPJs\Converter\NullConverter;

/**
 * Base Value Converter
 *
 * Base value converter to extend when creating "Converter" classes which
 * deal with the conversion of PHP values to Javascript
 *
 * @package     PHPJs
 * @subpackage  Converter
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
abstract class Converter implements Renderable
{

  protected $value;

  /**
   * Constructor
   *
   * @param mixed $value The value to represent
   */
  public function __construct($value)
  {
    $this->value = $value;
  }

  /**
   * Get a converter
   *
   * Convert the "$type" argument into a value "Converter" class which handles
   * the conversion of a PHP Value to Javascript
   *
   * @param mixed $type The type to convert
   * @throws \InvalidArgumentException Thrown when the value has no avaiable converter
   * @return Converter The converter which represents the $type
   */
  public static function getConverter($type)
  {
    if (is_array($type))
    {
      return new ArrayConverter($type);
    } elseif (is_string($type))
    {
      return new StringConverter($type);
    } elseif (is_null($type))
    {
      return new NullConverter($type);
    } elseif (is_numeric($type) || is_int($type))
    {
      return new IntegerConverter($type);
    } elseif (is_object($type))
    {
      return new ArrayConverter(get_object_vars($type));
    } elseif (is_float($type))
    {
      return new FloatConverter($type);
    } else
    {
      throw new \InvalidArgumentException(sprintf('Unable to convert value "%s" to javascript.',
        get_type($type)));
    }
  }

  public final function getUuid() {
    throw new BadMethodCallException('Function "getUuid" is not supported for converters');
  }

  public function render() {
    return (string) $this;
  }
}