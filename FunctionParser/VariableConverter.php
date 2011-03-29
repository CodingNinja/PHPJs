<?php

namespace PHPJs\FunctionParser;

class VariableConverter extends AbstractTag {

    public function __toString() {
        return (string) $this->token;
    }
}