#!/bin/bash
rm -f x
cat php_testfiles/literals_test.php
echo '--'
./phplint.py php_testfiles/literals_test.php > x

if [[ "$?" -eq 0 ]]; then
    cat x
    colordiff php_testfiles/literals_test.php x
fi
