<?php
    $ini_array = parse_ini_file("data/config.ini");
    echo $ini_array["version"];
?>