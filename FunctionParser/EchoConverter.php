<?php

namespace PHPJs\FunctionParser;


class EchoConverter extends AbstractTag {

    public function __toString() {
        $tokens = $this->token->tokenStream->tokens();
        $i = $start = $this->token->id + 1;
        $code = '';
        $variables = array();

        while(!$tokens[$i] instanceof \PHP_Token_SEMICOLON) {
            $code .= (string) $tokens[$i];
            if($i > 100) {
                die('failed');
            }
            $i++;
        }
        $this->token->tokenStream->seek($i);

        return sprintf('console.log(%s);', \PHPJs\FunctionParser::parse($code));
    }
}