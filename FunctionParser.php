<?php

namespace PHPJs;

include('PHP/Token/Stream.php');

class FunctionParser {

    protected $stream = false;

    protected $ret = '';

    public function __construct($code) {
        $this->stream = new \PHP_Token_Stream('<?php ' . $code . ' ?>');
        $this->doParse();
    }

    public static function parse($toks) {
        return new FunctionParser($toks);
    }

    public function doParse() {
        foreach($this->stream as $num => $tok) {
            // $this->ret .=
            $this->ret .= $this->convert($tok);
        }
    }

    public function convert($tok) {
        if($tok instanceof \PHP_Token_WHITESPACE) {
            return ' ';
        }
        if($tok instanceof \PHP_Token_STRING) {
            $val = (string) $tok;
            if(preg_match('/[a-zA-Z\_]+/', $val)) {
                $class = $this->getFunctionParserClass($tok);
                $tokParser = new $class($tok, $tok->tokenStream);
                return (string) $tokParser;
            }
        }

        switch(get_class($tok)) {
            case "PHP_Token_OPEN_TAG":
            case "PHP_Token_CLOSE_TAG":
                break;
            case "PHP_Token_STRING":
            case "PHP_Token_VARIABLE":
            case "PHP_Token_OPEN_BRACKET":
            case "PHP_Token_CLOSE_BRACKET":
            case "PHP_Token_SEMICOLON":
            case "PHP_Token_CLOSE_CURLY":
            case "PHP_Token_STRING":
            case "PHP_Token_STRING":
            case "PHP_Token_STRING":
            case "PHP_Token_STRING":
            case "PHP_Token_STRING":
                return (string) $tok;
                break;
        }

        $class = $this->getTokenParserClass($tok);
        if(!class_exists($class, true)) {
            throw new \Exception(sprintf('Unsupported PHP Code "%s". Token: "%s"', $tok, get_class($tok)));
        }

        $tokParser = new $class($tok, $tok->tokenStream);

        return (string) $tokParser;
    }

    public function getTokenParserClass($tok) {
        $class = str_replace('_', ' ', substr(get_class($tok), 10));
        $class = str_replace(' ', '', ucwords(strtolower($class)));
        return '\\PHPJs\\FunctionParser\\' . $class . 'Converter';
    }

    public function getFunctionParserClass($tok) {
        $class = str_replace('_', ' ', (string) $tok);
        $class = str_replace(' ', '', ucwords(strtolower($class)));
        return '\\PHPJs\\FunctionParser\\FunctionConverter\\' . $class . 'Converter';
    }

    public function __toString() {
        return $this->ret;
    }
}