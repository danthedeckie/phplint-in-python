'''
    Much boilerplate-reduced very simple cleaning tests.  Not a replacement
    for all unittest based tests, but for simple stuff, much easier to write.

    The PHP files in this dir are:
    
    test name
    ----
    <input>
    ----
    <expected output>
    ====

    with as many tests as you want.  this script checks that phplint does
    actually do what is expected in each case.
    

'''

import sys
from os.path import dirname, abspath, join

sys.path.append(dirname(dirname(dirname(abspath(__file__)))))

import phplint

parser = phplint.PHPParser(False)

# colorful warning message showing what failed:
WARNING = '''
input:
---\033[93m
%s\033[0m
---
should have output:
---\033[94m
%s\033[0m
---
actually output:
---\033[91m
%s\033[0m
---
'''

with open(sys.argv[1]) as f:
    all_text = f.read()[:-1]
    tests = all_text.split('\n====\n')

for testcase in tests:
    testname, before, expected = testcase.split('\n----\n')
    output = parser.parse(before)
    if output != expected:
        print (testname + '\n-----')
        parser.display_warnings = True
        parser.parse(before)
        print (WARNING % (before, expected, output))
        exit(1)
    else:
        print '.'

