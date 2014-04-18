#!/bin/bash

# constant values:

TESTDIR='testfiles'
OUTPUTDIR="$TESTDIR/output"

# init:

rm -rf "$OUTPUTDIR"
mkdir "$OUTPUTDIR"

die() {
    echo "$1"
    exit 1
}

test_clean() {
    ORIGINAL="$TESTDIR/cleaning/$1"
    EXPECTED="$TESTDIR/cleaning/should_be/$1"
    CLEANED="$OUTPUTDIR/$1"
    WARNINGFILE="$OUTPUTDIR/$1.warnings"

    php -l "$ORIGINAL" > /dev/null || die "invalid php! ($1)"

    ./phplint.py "$ORIGINAL" > "$CLEANED" 2> "$WARNINGFILE"
    if [[ $? -ne 0 ]]; then
        echo "DIED! ($1)"
        echo 'stdout:'
        cat "$CLEANED"
        echo '--'
        echo "stderr:"
        cat "$WARNINGFILE"
        exit 1
    fi

    diff "$CLEANED" "$EXPECTED" > /dev/null
    if [[ $? -ne 0 ]]; then
        echo "phplint did NOT give the expected output! ($1)"
        colordiff -c1 "$CLEANED" "$EXPECTED"
        exit 1
    fi
    
    echo "$1 passed."
}

test_clean htmlblock_within_function.php
test_clean literals_test.php
test_clean function_args.php
