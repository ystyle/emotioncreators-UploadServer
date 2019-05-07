<?php
    function checkCloseErrorReport() {
        $ini_array = parse_ini_file("data/config.ini");
        if($ini_array["close_error_report"]) {
            error_reporting(0);
        }
    }

    function writeError($str) {
        date_default_timezone_set("PRC");
        $pFile = fopen("error.txt", "a+") or die("无法读取文件");
        fwrite($pFile, date("Y-m-d H:i:s",time()). ": ".$str."\n");
		fclose($pFile);
    }

    function safe_GetPost($index) {
        if(!isset($_POST[$index])) {
            exit("ERROR_NO_PARAM");
        }
        return $_POST[$index];
    }
    
    function safe_GetFileTmpName($index) {
        if(!isset($_FILES[$index])) {
            exit("ERROR_NO_UPLOAD_FILE");
        }
        if(!isset($_FILES[$index]["tmp_name"])) {
            exit("ERROR_NO_UPLOAD_FILE");
        }
        if(empty($_FILES[$index]["tmp_name"])) {
            exit("ERROR_NO_UPLOAD_FILE");
        }
        return $_FILES[$index]["tmp_name"];
    }
?>