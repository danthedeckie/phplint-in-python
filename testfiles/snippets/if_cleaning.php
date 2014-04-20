single-line non-bracketed if only
----
<?php
    if ($temperature < 21) echo "cool";
?>
----
<?php
    if ($temperature < 21) {
        echo "cool";
    }
?>
====
single-line if and else non-bracketed
----
<?php
    if ($temperature < 21) echo "cool";
    else echo "not cool";
?>
----
<?php
    if ($temperature < 21) {
        echo "cool";
    } else {
        echo "not cool";
    }
?>
====
if, else if, else, non-bracketed next-line
----
<?php
    if ($temperature < 21)
        echo "cool";
    else if ($temperature<25)
        echo "less cool";
    else
        echo "not cool";
?>
----
<?php
    if ($temperature < 21) {
        echo "cool";
    } else if ($temperature < 25) {
        echo "less cool";
    } else {
        echo "not cool";
    }
?>
====
if, else if, elseif, else non-bracketed next-line
----
<?php
    if ($temperature < 21)
        echo "cool";
    else if ($temperature<25)
        echo "less cool";
    elseif ($temperature<35)
        echo "pretty warm";
    else
        echo "need AC";
?>
----
<?php
    if ($temperature < 21) {
        echo "cool";
    } else if ($temperature < 25) {
        echo "less cool";
    } elseif ($temperature < 35) {
        echo "pretty warm";
    } else {
        echo "need AC";
    }
?>
====
if non-bracketed next-line with usual bug...
----
<?php
    if ($temperature < 21)
        echo "pretty cool";
        turn_on_heating();
?>
----
<?php
    if ($temperature < 21) {
        echo "pretty cool";
    }
    turn_on_heating();
?>
