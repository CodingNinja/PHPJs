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

use PHPJs\Component;
use PHPJs\Converter;
use PHPJs\Converter\LiteralConverter;

/**
 * Array Converter
 *
 * Convert's a PHP array into the javascript representation. It wil also
 * recurse through the values of the array and make sure that they will also
 * be converted to the proper {@link Converter} class.
 *
 * @package     PHPJs
 * @subpackage  Converter
 * @author      David Mann <ninja@codingninja.com.au>
 * @copyright   David Mann
 */
class ArrayConverter extends Converter
{
    protected $dependencies = array ();
    
    protected $isAssociative = false;
    
    /**
     * ArrayConverter::__construct()
     *
     * @param mixed $value
     * @return
     */
    public function __construct(array $value) {
        $this->addValues ( $value );
    }
    
    public function addValues(array $value) {
        
        $values = array ();
        foreach ( $value as $key => $value ) {
            if ($value instanceof Component) {
                $values [$key] = new LiteralConverter ( $value->getUuid () );
                $this->dependencies [] = $value;
            } elseif ($value instanceof Converter) {
                $values [$key] = $value;
            } else {
                $values [$key] = self::getConverter ( $value );
            }
            
            if (is_string ( $key ) && ! is_numeric ( $key )) {
                $this->setIsAssoc ( true );
            }
        }
        
        $this->value = $values;
    }
    
    public function setIsAssoc($bool) {
        $this->isAssociative = $bool;
        
        return $this;
    }
    
    public function getIsAssoc() {
        return $this->isAssociative;
    }
    
    /**
     * ArrayConverter::getDependencies()
     *
     * @return array An array of {@link \PHPJs\Component}'s
     */
    public function getDependencies() {
        return $this->dependencies;
    }
    
    /**
     * ArrayConverter::__toString()
     *
     * @return string The array in "javascript" form
     */
    public function __toString() {
        if (! $this->isAssociative) {
            return '[' . implode ( ', ', array_values ( $this->value ) ) . ']';
        } else {
            $retval = array ();
            foreach ( $this->value as $key => $value ) {
                $retval [] = ( string ) $key . ': ' . ( string ) $value;
            }
            
            return '{' . implode ( ', ', $retval ) . '}';
        }
    }
    
    public function push($value, $key = null) {
        $this->addValues(array($key => $value));
        
        return $this;    
    }
}