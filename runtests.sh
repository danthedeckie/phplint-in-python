#!/bin/bash

# constant values:

TESTDIR='testfiles'
OUTPUTDIR="$TESTDIR/output"
SNIPPETS="$TESTDIR/snippets"
OUTPUT_LINES=1

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
        echo "----------"
        echo "phplint did NOT give the expected output! ($1)"
        cat "$WARNINGFILE"
        colordiff -C $OUTPUT_LINES "$CLEANED" "$EXPECTED"
        echo "----------"
        #exit 1
    else 
        echo "$1 passed."
    fi
}

if [[ -n "$1" ]]; then
    OUTPUT_LINES=3 test_clean "$1"
else
    echo "Testing snippets"
    for SNIP in $(find "$SNIPPETS" -name '*.php' -print); do
        python "$SNIPPETS/test_snippets.py" "$SNIP"
        if [[ $? -ne 0 ]]; then
            echo "in $SNIP"
            exit 2
        fi
    done

    test_clean htmlblock_within_function.php
    test_clean literals_test.php
    test_clean function_args.php
    test_clean blocks_without_brackets.php
fi
