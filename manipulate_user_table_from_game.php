<?php
    $method = !empty($_GET) ? "GET" : "POST";
    if($method == "GET") {
        exit("ERROR_GET");
    }
    if(!isset($_POST["mode"])) {
        exit("ERROR_MODE_NOT_SET");
    }
    $mode = $_POST["mode"];

    include_once("inc/utils.php");
    include_once("inc/mysql/mysqlHandle.php");
    include_once("inc/mysql/db_User.php");
    
    checkCloseErrorReport();
    mysqlHandle::MySQL_Connect();

    if($mode == 0) { //GetSystemInfo
        $package = base64_encode(file_get_contents("data/server/package.json"));
		$abtop = base64_encode(file_get_contents("data/server/abtop.json"));
		$tag = base64_encode(file_get_contents("data/server/tag.json"));
		echo $package."\t".$abtop."\t".$tag;
    }
    else if($mode == 1) { #UpdateUserInfo
        $uuid = safe_GetPost("uuid");
        $passwd = safe_GetPost("passwd");

        if(db_User::IsExistUser($uuid, $passwd) == FALSE) {
            db_User::CreateUser($uuid, $passwd);
        }
        $userInfo = db_User::GetUserInfo($uuid, $passwd);
        if($userInfo == NULL) {
            return;
        }
        $writeString = base64_encode($userInfo["userIdx"])."\t".
                        base64_encode($userInfo["mapPoint"])."\t".
                        base64_encode($userInfo["posePoint"])."\t".
                        base64_encode($userInfo["scenePoint"])."\t".
                        base64_encode($userInfo["mapCount"])."\t".
                        base64_encode($userInfo["poseCount"])."\t".
                        base64_encode($userInfo["sceneCount"])."\t";

        echo $writeString;
    }
    else if($mode == 2) { #UpdateHandleName
        $uuid = safe_GetPost("uuid");
        $passwd = safe_GetPost("passwd");
        $handle_name = safe_GetPost("handle_name");
        if(db_User::UpdateUserName($uuid, $passwd, $handle_name)) {
            echo "ok";
        }
    }
    else if($mode == 3) { # GetAllUsers
        $lstUserInfo = db_User::GetAllUsers();
        $writeLst = array();
        foreach($lstUserInfo as $userInfo) {
            $tempString = base64_encode($userInfo["userIdx"])."\t".
                        base64_encode($userInfo["handleName"])."\t".
                        base64_encode($userInfo["mapPoint"])."\t".
                        base64_encode($userInfo["posePoint"])."\t".
                        base64_encode($userInfo["scenePoint"])."\t".
                        base64_encode($userInfo["mapCount"])."\t".
                        base64_encode($userInfo["poseCount"])."\t".
                        base64_encode($userInfo["sceneCount"]);

            array_push($writeLst,  $tempString);
        }
        if(count($writeLst) > 0) {
            echo implode("\n", $writeLst);
        }
    }
?>