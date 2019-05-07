<?php
    $method = !empty($_GET) ? "GET" : "POST";
    if($method == "GET") {
        exit("ERROR_GET");
    }
    if(!isset($_POST["version"])) {
        exit("ERROR_VERSION_NOT_SET");
    }
    echo "0\t0";
?>