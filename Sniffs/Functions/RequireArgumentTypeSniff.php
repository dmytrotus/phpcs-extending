<?php

namespace MyStandard\Sniffs\Functions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireArgumentTypeSniff implements Sniff
{
    /**
     * Returns an array of token types that this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_FUNCTION, T_CLOSURE];
    }

    /**
     * Processes this sniff, when one of its registered tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Get the function name, if available
        $functionName = $phpcsFile->getDeclarationName($stackPtr);
        if ($functionName === null) {
            // Use a generic name for anonymous functions
            $functionName = 'anonymous function';
        }

        // Get the function's argument list
        $openParen = $tokens[$stackPtr]['parenthesis_opener'];
        $closeParen = $tokens[$stackPtr]['parenthesis_closer'];

        // Loop through the function's arguments
        for ($i = $openParen + 1; $i < $closeParen; $i++) {
            if ($tokens[$i]['code'] === T_VARIABLE) {
                // Check if the argument has a type hint
                $hasTypeHint = false;
                for ($j = $i - 1; $j > $openParen; $j--) {
                    if (in_array($tokens[$j]['code'], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT])) {
                        continue;
                    }
                    if (in_array($tokens[$j]['code'], [T_STRING, T_NS_SEPARATOR, T_ARRAY_HINT, T_CALLABLE, T_NULLABLE])) {
                        $hasTypeHint = true;
                    }
                    break;
                }

                if (!$hasTypeHint) {
                    $error = 'Argument "%s" of %s does not have a type hint.';
                    $data = [
                        $tokens[$i]['content'],
                        $functionName
                    ];
                    $phpcsFile->addError($error, $i, 'MissingTypeHint', $data);
                }
            }
        }
    }
}
