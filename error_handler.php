<?php

function error_handler($errno, $errstr, $errfile, $errline, $errcontext) {
    $gabimet = [
        E_ERROR             => "Gabim fatal (E_ERROR)",
        E_WARNING           => "Vërejtje (E_WARNING)",
        E_PARSE             => "Gabim në analizim sintaksor (E_PARSE)",
        E_NOTICE            => "Vërejtje (E_NOTICE)",
        E_USER_ERROR        => "Gabim nga përdoruesi (E_USER_ERROR)",
        E_USER_WARNING      => "Vërejtje nga përdoruesi (E_USER_WARNING)",
        E_USER_NOTICE       => "Njoftim nga përdoruesi (E_USER_NOTICE)",
    ];
    $llojiGabimit = $gabimet[$errno] ?? "Gabim i panjohur ($errno)";
    echo "<div style='color:red; border:1px solid red; background:#fff0f0; padding:10px; margin:10px 0;'>";
    echo "<b>[$llojiGabimit]</b> - $errstr<br>";
    echo "<i>Dosja:</i> $errfile <br>";
    echo "<i>Rreshti:</i> $errline <br>";
    echo "</div>";
    if ($errno == E_USER_ERROR || $errno == E_ERROR) exit(1);
}
set_error_handler('error_handler');
?>
