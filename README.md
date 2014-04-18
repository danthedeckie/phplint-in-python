This is a for-fun / for-education PHP linter / tester, written in Python.

I got fed up with cleaning up PHP, trying to make it work nicely
I want something like gofmt for PHP, and none of the other options
out there seemed to do quite what I wanted...

Not that this one is, "production ready" mind you.  You should treat the output of this with care and inspect
things manually afterwards.  Please send me any test cases that bring up errors!

It's currently work in progress, somewhat messily written (sorry!), and still has a long way to go.

There are some missing features, but I'm working on them.

The intention is that this can either be run as a 'make sure my php is well
formatted' before committing, or else to clean up a load of legacy code and
format it correctly.

## usage:

The recommended way to use it currently is:

    php -l file.php  # test that it is actually valid php...
    phplint.py file.php > new_file.php # make a copy
    php -l new_file.php # check that phplint hasn't broken the php
    colordiff -w file.php new_file.php # look at all the differences that aren't space related

and then look at the files manually to make sure it all looks sane.

GPL Licenced.