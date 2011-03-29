<?php

namespace PHPJs\FunctionParser;

class ForeachConverter extends AbstractTag {

    public function __toString() {
        $tokens = $this->token->tokenStream->tokens();
        $i = $start = $this->token->id;
        $ret = '';
        $variables = array();

        while(!$tokens[$i] instanceof \PHP_Token_OPEN_CURLY) {
            $token = $tokens[$i];
            switch(get_class($token)) {
                case "PHP_Token_VARIABLE":
                    $variables[] = (string) $token;
                    break;
                default:
                    break;
            }
            $i++;
        }

        reset($variables);
        $ret = sprintf('for(%s in %s) {', $variables[count($variables) - 1], $variables[0], (isset($variables[2]) ? $variables[1] .' = ' . $variables[0] .'['.$variables[count($variables) - 1].'];' : ''));
        $this->token->tokenStream->seek($i);
        return $ret;
    }
}