<?php
namespace F3\Sniffs\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\PHP_CodeSniffer_File;
use PHP_CodeSniffer\Sniffs\Sniff;

class FunctionFirstLineSniff implements Sniff
{
    public function register()
    {
        return [
            T_FUNCTION,
            T_CLOSURE,
        ];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            return;
        }
        $index = $tokens[$stackPtr]['scope_opener'] + 1;
        $lineBreaks = 0;
        while ($tokens[$index]['code'] === T_WHITESPACE && preg_match('/\n/', $tokens[$index]['content'])) {
            $index++;
            $lineBreaks++;
        }
        if ($lineBreaks > 1) {
            $error = 'Functions must not begin with an empty line';
            $phpcsFile->addError($error, $index - 1, 'EmptyLine');
        }
    }
}
