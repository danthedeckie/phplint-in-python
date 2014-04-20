import sys
import unittest
import os.path

sys.path.append(os.path.dirname(os.path.dirname(__file__)))

import phplint

def remove_indent(text):
    ''' if a string starts with \n, then take the next line indent as
        'no indent', and replace the string with that. '''
    if text.startswith('\n'):
        to_remove = text[:len(text) - len(text.lstrip())]
        text = text.replace(to_remove, '\n')[1:]
    return text

class PHPLintTestCase(unittest.TestCase):
    ''' testcase base class for running phplint testcases. '''

    def setUp(self):
        self.parser = phplint.PHPParser(False)

    def ae(self, inputtext, expectedoutput):
        inputtext = remove_indent(inputtext)
        expectedoutput = remove_indent(expectedoutput)
        output = self.parser.parse(inputtext)
        try:
            self.assertEqual(output, expectedoutput)
        except:
            sys.stderr.write('--- got: ---\n')
            sys.stderr.write(output)
            sys.stderr.write('\n--- instead of ---\n')
            sys.stderr.write(expectedoutput)
            raise
