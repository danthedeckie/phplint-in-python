# PHP features to add which behave in special ways:

## Keyword blocks:
- function, including 'using'. - currently will fail with function names "for..." or starting with other reserved words.
- switch, case
- if, else, elseif - Needs more unit tests, but otherwise looks good!
- do, loop, while, for, foreach *wip*
- array(1,2,3)
- array(a => b. c => d) - looks good, needs tests

## language syntax:
- [new, style, arrays]
- [new => style, arrays=>like_this]
- &reference var stuff
- number literals
- if: endif syntax
- namespaces

# phplint features:

- by default only complain about issues, don't try to fix them.
- options to parser from command line.
- colourful output
- test suite
  - more tests
  - automatically finding new tests
  - invalid php tests
  - internal function tests, as well as the current output tests
  - non-cleaning tests (make sure it doesn't change *anything*!)
  - test for 'idempotent' stuff, you should be able to run it again
    and again on the same file, without the output changing. (after the first time)
- documentation
- warnings about similar words & variables
- including other files.
- class and function names that don't match PSR standards.
- 'too much indentation' warning.
- total score (as pylint does).
- vim/emacs/etc quickfix window style output
