<?php

namespace PHPJs\FunctionParser;

abstract class AbstractTag {

    protected $token = false;

    protected $stream = false;

    public function __construct($tok, $stream) {
        $this->stream = $stream;
        $this->token = $tok;
    }

    abstract public function __toString();
}