<?php

namespace PHPJs\FunctionParser;

class FunctionConverter extends AbstractTag {

    public function __toString() {
        $args = $this->token->getArguments();
        $name = $this->token->getName();
        $this->token->tokenStream->seek($this->token->getFunctionCodeStartId());

        return sprintf('function %s(%s) {', (($name === \PHP_Token::ANONYMOUS) ? '' : $name), $this->makeArgs($args));
    }

    public function makeArgs($args) {
        $ret = array();

        foreach($args as $variable => $config) {
            $ret[] = (string) $variable;
        }

        return implode(', ', $ret);
    }

}