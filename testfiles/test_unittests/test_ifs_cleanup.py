'''
    Unit tests for testing PHPLint cleans up if statements correctly.
'''

from phplint_unittest_extras import PHPLintTestCase

class TestIfStatements(PHPLintTestCase):
    def test_if_good(self):
        self.ae('''
                <?php
                if ($x == 21) {
                    echo "cool";
                }
                ''', '''
                <?php
                if ($x == 21) {
                    echo "cool";
                }
                ''')

    def test_if_convert(self):
        self.ae('''
                <?php
                if ($x == 21) echo "cool";
                ''', '''
                <?php
                if ($x == 21) {
                    echo "cool";
                }
                ''')

    def test_if_else(self):
        self.ae('''
                <?php
                if ($x == 21) echo "cool";
                else echo "not cool";
                ''', '''
                <?php
                if ($x == 21) {
                    echo "cool";
                } else {
                    echo "not cool";
                }
                ''')

    def test_if_elseif(self):
        self.ae('''
                <?php
                if ($x == 21) echo "cool";
                elseif ($x == 99) echo "less cool";
                ''', '''
                <?php
                if ($x == 21) {
                    echo "cool";
                } elseif ($x == 99) {
                    echo "less cool";
                }
                ''')

    def test_if_elseif(self):
        self.ae('''
                <?php
                if ($x == 21) echo "cool";
                elseif ($x == 99) echo "less cool";
                else echo "not cool";
                ''', '''
                <?php
                if ($x == 21) {
                    echo "cool";
                } elseif ($x == 99) {
                    echo "less cool";
                } else {
                    echo "not cool";
                }
                ''')

