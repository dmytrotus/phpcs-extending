<?php

namespace MyStandard\Sniffs\Functions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireReturnTypeSniff implements Sniff
{
    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Get the function name
        $functionName = $phpcsFile->getDeclarationName($stackPtr);

        // Skip __construct
        if ($functionName === '__construct') {
            return;
        }

        // Find the position of the opening parenthesis of the function declaration.
        $openParenthesis = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr);

        // Find the position of the closing parenthesis of the function declaration.
        $closeParenthesis = $phpcsFile->findNext(T_CLOSE_PARENTHESIS, $openParenthesis);

        // Find the position of the colon after the closing parenthesis (if any).
        $colon = $phpcsFile->findNext(T_COLON, $closeParenthesis, $closeParenthesis + 2);

        // Find the position of the opening curly brace of the function body.
        $openCurlyBrace = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $closeParenthesis);

        if ($colon === false || $colon > $openCurlyBrace) {
            $error = 'Return type declaration is required for all functions';
            $phpcsFile->addError($error, $stackPtr, 'MissingReturnType');
        }
    }
}
