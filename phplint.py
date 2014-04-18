#!/usr/bin/python
'''
    phplint.py - (C) 2014 Daniel Fairhead
    GPL3.0 licenced
    -------------------------------------
    a simple php linter/formatter in python.
    WORK IN PROGRESS.

'''

from __future__ import print_function
import sys

# this could/should be expanded to full UTF-8 capacity:

valid_letters = 'abcdefghijklmnopqrstuvwxyz' \
                'ABCDEFGHIJKLMNOPQRSTUVWXYZ' \
                '1234567890_'

OPERATORS = ['.', '+', '-', '*', '/', '&', '^', '%', '|', '?', ':', '++', '--',
             '.=', '+=', '-=', '*=', '/=', '&&', '||', '==', '===', '=>', '->',
             '::']

# step_back sort by length...
OPERATORS.sort(lambda a,b:cmp(len(b), len(a)))

KEYWORD_BLOCK_THINGS = ['for', 'function', 'while', 'foreach', 'if', 'do',
                        'switch', 'class']

###############################################################3

class Parser(object):
    ''' a generic parser object. '''

    line_no = 1
    chr_no = 0
    text = ''
    position = 0
    text_length = 0
    current_indent = ''
    expected_indent_level = 0
    k_and_r_braces = True

    def __init__(self, warn=True, clean=True):
        ''' constructor '''

        self.display_warnings = warn
        self.cleanup = clean
        self.variables = []

    def continue_1_chr(self):
        ''' do this on every character, allows easy line and character
            counting. '''

        if self.position < self.text_length:
            self.position += 1

        if self.next_chr_is('\n'):
            self.line_no += 1
            self.chr_no = 0
        else:
            self.chr_no += 1

    def step_back(self, count=1):
        ''' go back <count> characters '''
        self.position -= count
        self.chr_no -= count

    def continue_chrs(self, count):
        ''' continue <count> number of characters '''

        for i in xrange(count):
            self.continue_1_chr()

    def next_chr_is(self, char):
        ''' test if the next character to be parsed is char '''
        try:
            return self.text[self.position] == char
        except IndexError:
            return False

    def next_chr_in(self, chars):
        ''' test if the next character is any of these characters '''

        cur = self.text[self.position]
        return cur in chars
        return any(cur == c for c in chars)

    def next_starts(self, *texts):
        ''' test if the next text to be read starts with this text,
            and return it'''
        for text in texts:
            if self.text[self.position:].startswith(text):
                return text
        return False

    def _not_at_end(self):
        ''' used by parsing functions internally to continue one character
            at a time, and call the 'continue_1_chr' function. '''

        if self.position < self.text_length:
            self.continue_1_chr()
            return True
        else:
            return False

    def warn(self, text, level=5):
        if self.display_warnings:
            print ("Warning(%i) [%i:%i]:  %s" % (level, self.line_no,
                                                 self.chr_no, text),
                                                 file=sys.stderr)


class PHPParser(Parser):
    ''' a PHP specific Parser object '''

    def string_literal(self):
        ''' read a string literal 'like this' or "like this", return it. '''

        initial_quote_mark = self.text[self.position]
        start_position = self.position
        
        while self._not_at_end():
            if self.next_chr_is('\\'):
                self.continue_1_chr()
            elif self.next_chr_is(initial_quote_mark):
                return self.text[start_position:self.position + 1]

    def multiline_comment(self):
        ''' read from /* to */ '''

        initial_indent = self.chr_no
        start = self.position

        while self._not_at_end():
            if self.next_starts('*/'):
                self.continue_1_chr()
                return self.text[start:self.position + 1]

    def inline_comment(self):
        ''' from // to the end of line. '''

        start = self.position

        while self._not_at_end():
            if self.next_chr_is('\n'):
                self.step_back()
                return self.text[start:self.position]

    def keyword_block(self):
        return self.text[self.position] # TODO

    def expression(self):
        output = ['(']
        start = self.position

        if self.cleanup:
            # remove initial spaces:
            while self._not_at_end():
                if not self.next_chr_in(' \t'):
                    self.step_back()
                    break

        while self._not_at_end():
            if self.next_chr_is(')'):
                if self.cleanup:
                    while output[-1] in ' \t':
                        output.pop()
                output.append(')')
                return ''.join(output)

            elif self.next_chr_is('('):
                output.append(self.expression())
            elif self.next_chr_is(';'):
                output.append(';')
                output.append(self.expect_space())
            else:
                output.append(self.text[self.position])

    def semicolon(self):
        return ';'

    def variable(self):
        start = self.position
        self.continue_1_chr() # advance past '$'

        while self._not_at_end():
            if not self.next_chr_in(valid_letters):
                varname = self.text[start:self.position] 
                if not varname in self.variables:
                    self.variables.append(varname)
                self.step_back()
                return varname

    def inline_html(self):
        start = self.position

        while self._not_at_end():
            if self.next_starts('<?php'):
                self.continue_chrs(5)
                return self.text[start:self.position]
        else:
            self.warn('End of file within PHP {} block!', 10)

    def line_indent(self, blocklevel=0):
        blanklines = ''
        if self.text[self.position] == '\n':
            start = self.position + 1
            linestart = start

        while self._not_at_end():
            if self.next_chr_is('\n'):
                self.warn('extra newline!')
                linestart = self.position+1
                blanklines += '\n'
                continue
            if not self.next_chr_in(' \t'):
                this_indent = self.text[linestart:self.position]
                if blocklevel and self.cleanup:
                    if self.current_indent != this_indent:
                        self.warn('oddball indentation!')
                else:
                    self.current_indent = this_indent

                self.step_back()
                return blanklines + self.current_indent

    def expect_newline(self):
        ''' after some things, we expect a new line! is that too much to
            ask? '''

        while self._not_at_end():
            if self.next_chr_is('\n'):
                self.step_back()
                return ''

            if self.next_chr_in(' \t'):
                continue
            elif self.next_starts('?>'):
                self.step_back()
                return ' ' # space before end of php block...
            else:
                self.step_back()
                return '\n' + self.current_indent

    def expect_space(self):
        ''' after operators, etc, we expect 1 space only. '''
        output = []

        while self._not_at_end():
            if self.next_chr_is('\n'):
                output.append('\n' + self.current_indent + '    ')
                continue # TODO is that right???
            elif self.next_chr_is(' '):
                continue
            else:
                if not output:
                    self.warn('expected space!')
                self.step_back()
                output.append(' ')
                return ''.join(output)

    def php_section(self, indent=0, indent_str=''):
        '''
            parse / cleanup a php block. a block is either between
            '<?php ... ?>' anything inside {}.  inside a {}, '?>...<?php' is
            treated as part of the block, not as the end of the current one.
        '''

        output = []

        while self._not_at_end():
            if self.next_starts('?>'):
                if not indent:
                    self.continue_1_chr()
                    return ''.join(output)
                else:
                    output.append(self.inline_html())
            elif self.next_chr_is('\n'):
                output.append('\n')
                output.append(self.line_indent(indent))
            elif self.next_chr_in(' \t') and not len(output):
                if indent:
                    self.step_back()
                    output.append(self.expect_newline())
                else:
                    output.append(self.text[self.position])
            elif self.next_chr_in('"\''):

                output.append(self.string_literal())

            elif self.next_starts('/*'):
                output.append(self.multiline_comment())

            elif self.next_starts('//'):
                output.append(self.inline_comment())
            elif self.next_chr_is('$'):
                output.append(self.variable())
            elif self.next_chr_is('('):
                output.append(self.expression())
            #elif self.next_starts(*KEYWORD_BLOCK_THINGS):
            #    output.append(self.keyword_block())
            elif self.next_chr_is('{'):
                output.append('{')
                if self.cleanup:
                    old_indent = self.current_indent
                    self.current_indent += '    '
                    output.append(self.expect_newline())

                output.append(self.php_section(indent + 1))

                if self.cleanup:
                    self.current_indent = old_indent

            elif self.next_chr_is('}'):
                if self.cleanup and output and self.k_and_r_braces:
                    if output[-1].endswith('    '):
                        output[-1] = output[-1][0:-4]
                output.append('}')
                return ''.join(output)
            elif self.next_chr_is(';'):
                if not len(output):
                    self.warn('semicolon at beginning of <?php section.')
                else:
                    if output[-1] in ' \t':
                        self.warn('space before semicolon')
                    elif output[-1] in ';\n':
                        self.warn('semicolon without line of code!')

                if self.cleanup:
                    while len(output) and output[-1] in '; \t\n':
                        output.pop()
                    if len(output):
                        output.append(';')

                    output.append(self.expect_newline())
                else:
                    output.append(self.semicolon())


            elif self.next_chr_is(','):
                if self.text[self.position-1] == ' ':
                    self.warn('space before comma!')

                output.append(',')

                if self.text[self.position+1] != ' ':
                    self.warn ('no space after comma')

                if self.cleanup:
                    output.append(' ')

            elif self.next_starts(*OPERATORS):
                op = self.next_starts(*OPERATORS)

                if op not in ('++', '--', '::', '->') \
                and self.text[self.position-1] != ' ':
                    self.warn('no space before ' + op)

                    if self.cleanup:
                        output.append(' ')

                output.append(op)
                self.continue_chrs(len(op)-1)

                if op not in ('++', '--', '::', '->'):
                    output.append(self.expect_space())

            elif self.next_chr_in(valid_letters):
                start = self.position
                while self._not_at_end():
                    if not self.next_chr_in(valid_letters):
                        self.step_back()
                        output.append(self.text[start:self.position + 1])
                        break

            else:
                output.append(self.text[self.position])

        return ''.join(output)


    def parse(self, text):
        ''' the initial 'parse-a-php-file' function. Assumes that it is NOT
            starting inside a <?php block. '''

        self.text = text
        self.text_length = len(text)
        self.position = -1

        output = []

        while self._not_at_end():
            if self.next_starts('<?php'):
                self.continue_chrs(4)

                output.append('<?php')

                php_block = self.php_section()

                output.append(php_block)
                output.append('?>')
            else:
                try:
                    output.append(self.text[self.position])
                except IndexError:
                    self.warn('End of file OUTSIDE of <?php block...', 1)
                    break

        return ''.join(output)


def main(filename):
    with open(filename, 'r') as fh:
        input_text = fh.read()

    p = PHPParser()
    output_text = p.parse(input_text)
    print (output_text, end='')
    print ('Variables:', sorted(p.variables), file=sys.stderr)


if __name__ == '__main__':
    import sys
    if len(sys.argv) > 1:
        main(*sys.argv[1:])
    else:
        print ("Usage:")
        print ("phplint.py <filename>")
        exit(1)
