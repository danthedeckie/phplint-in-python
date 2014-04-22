simple function w/o args
----
<?php
function x(){
    return 42;
 }
?>
----
<?php
function x()
{
    return 42;
}
?>
====
simple function with 1 arg
----
<?php
function x($y){
    return 42;
 }
?>
----
<?php
function x($y)
{
    return 42;
}
?>
====
simple function with 2 arg
----
<?php
function x($y,$z){
    return 42;
 }
?>
----
<?php
function x($y, $z)
{
    return 42;
}
?>
====
function with 'correct' spacing
----
<?php
function x()
{
    return 99;
}
?>
----
<?php
function x()
{
    return 99;
}
?>
