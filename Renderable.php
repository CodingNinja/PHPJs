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

interface Renderable
{
    public function getUuid();
    
    public function __toString();
    
    public function render();
}