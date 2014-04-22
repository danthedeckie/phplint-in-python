crowded
----
<?php
$x = (1*2/80%($a++));
?>
----
<?php
$x = (1 * 2 / 80 % ($a++));
?>
====
extra spacing
----
<?php
    $x = (1*2   / 89 %($a-$b++) );
?>
----
<?php
    $x = (1 * 2 / 89 % ($a - $b++));
?>
====
inside function
----
<?php
function x($a,$b) {
    return (1*2   / 89 %($a-$b++) );
}
?>
----
<?php
function x($a, $b)
{
    return (1 * 2 / 89 % ($a - $b++));
}
?>
====
array, single-line
----
<?php
$x = array(1,2,3,4,   5);
?>
----
<?php
$x = array(1, 2, 3, 4, 5);
?>
====
array, key => value, single line.
----
<?php
$x = array('a' => "the", 'b'=>"answer",'c'    =>    "is", 'd'=>42);
?>
----
<?php
$x = array('a' => "the", 'b' => "answer", 'c' => "is", 'd' => 42);
?>
====
array, multi-line
----
<?php
$x = array( 1,
            2,
            3, 4, 5, // things
        6,      7,
8, 9);
?>
----
<?php
$x = array( 1,
            2,
            3, 4, 5, // things
            6, 7,
            8, 9);
?>
====
array, single-line, in function
----
<?php
function y {
$x = array(1,2,3,4,   5);
}
?>
----
<?php
function y
{
    $x = array(1, 2, 3, 4, 5);
}
?>
====
array, key => value, single line.
----
<?php
function y { $x = array('a' => "the", 'b'=>"answer",'c'    =>    "is", 'd'=>42); }
?>
----
<?php
function y
{
    $x = array('a' => "the", 'b' => "answer", 'c' => "is", 'd' => 42);
}
?>
====
array, multi-line
----
<?php
function y
{
$x = array( 1,
            2,
            3, 4, 5, /* inline comment... */
        6,      7,
8, 9); }
?>
----
<?php
function y
{
    $x = array( 1,
                2,
                3, 4, 5, /* inline comment... */
                6, 7,
                8, 9);
}
?>
