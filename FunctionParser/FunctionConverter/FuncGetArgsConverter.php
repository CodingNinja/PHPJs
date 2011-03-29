<?php

namespace PHPJs\FunctionParser\FunctionConverter;

use PHPJs\FunctionParser\AbstractTag;
class FuncGetArgsConverter extends AbstractTag {
    public function __toString() {
        $tokens = $this->token->tokenStream->tokens();
        $i = $start = $this->token->id;
        $ret = '';
        $variables = array();
        $stack = 0;

        while(!$tokens[$i] instanceof \PHP_Token_CLOSE_BRACKET || $stack > 0) {
            if($tokens[$i] instanceof \PHP_Token_OPEN_BRACKET) {
                $stack--;
            }elseif($tokens[$i] instanceof \PHP_Token_CLOSE_BRACKET) {
                $stack++;
            }

            $i++;
        }

        $this->token->tokenStream->seek($i);
        return 'arguments';
    }
}