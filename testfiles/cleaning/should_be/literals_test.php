<?php

    /* this is a comment */
    ?><!doctype html><?php // inline!

    function x($y) {
        $thing = $y * $y;
        printf('text %s', "more.");
        ;
        echo "te\| \"xt" . "more";
        ?> stuff... <?php
        ;
        echo 'te\| \"xt' . "other" . "and" . " stuff";
        $x = 21 + (9 + 11 / 89) + 2;
        $x -= 4;
        $x *= 21;

        for($y = 1; $y < 42; $y++) {
            echo $y;
        } // an inline comment x=$y.

        while (True) {
            ++$x;
        }

    }

 while(1 === OTHER) {
     x();
     x();
     x();
     x();
 }

$X = Thing::stuff($value);
$X->stuff($value);


?> and more <?php echo x(21); ?> stuff...
