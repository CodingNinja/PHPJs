<?php

namespace PHPJs\FunctionParser;

class EqualConverter extends AbstractTag {

    public function __toString() {
        return (string) $this->token;
    }
}