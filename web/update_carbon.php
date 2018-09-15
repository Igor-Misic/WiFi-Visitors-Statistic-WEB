<?php

function updateCarbon($id, $value) {
    echo "\n$id $value " . time() . "\n";
    $line = "$id $value " . time() . "\n";

    $fp = fsockopen('127.0.0.1', 2003, $err, $errc, 1);
    fwrite($fp, $line);

    fclose($fp);
}
?>