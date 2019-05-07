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
    include_once("inc/mysql/db_Chara.php");

    checkCloseErrorReport();
    mysqlHandle::MySQL_Connect();

    if($mode == 0) { //Get All Info
        $lstCharaInfo = db_Chara::GetAll();
        $writeLst = array();
        foreach($lstCharaInfo as $charaInfo) {
            $tempString = base64_encode($charaInfo["idx"])."\t".
                        base64_encode($charaInfo["uid"])."\t".
                        base64_encode($charaInfo["userID"])."\t".
                        base64_encode($charaInfo["name"])."\t".
                        base64_encode($charaInfo["voicetype"])."\t".
                        base64_encode($charaInfo["birthmonth"])."\t".
                        base64_encode($charaInfo["birthday"])."\t".
                        base64_encode($charaInfo["bloodtype"])."\t".
                        base64_encode($charaInfo["comment"])."\t".
                        base64_encode($charaInfo["package"])."\t".
                        base64_encode($charaInfo["sex"])."\t".
                        base64_encode($charaInfo["height"])."\t".
                        base64_encode($charaInfo["bust"])."\t".
                        base64_encode($charaInfo["hair"])."\t".
                        base64_encode($charaInfo["dlCount"])."\t".
                        base64_encode($charaInfo["weekCount"])."\t".
                        base64_encode($charaInfo["updateTime"])."\t".
                        base64_encode($charaInfo["update_idx"])."\t".
                        base64_encode("0");

            array_push($writeLst,  $tempString);
        }
        if(count($writeLst) > 0) {
            echo implode("\n", $writeLst);
        }
    }
    else if($mode == 1) { //Get Thumbnail
        $indexs = safe_GetPost("indexs");

        $lstIndex = explode("\t", $indexs);
        $lstThumbnail = array();
        foreach($lstIndex as $index) {
            array_push($lstThumbnail,  db_Chara::GetThumbnail($index));
        }
        if(count($lstThumbnail) > 0) {
            echo implode("\t", $lstThumbnail);
        }
        else {
            echo "ERROR_NO_CHARA";
        }
    }
    else if($mode == 2 || $mode == 3) { //#Upload 2 / Update 3
        $charaInfo = array();

        if($mode == 2) {
            $charaInfo["uid"] = safe_GetPost("chara_uid");
        }
        else {
            $charaInfo["index"] = safe_GetPost("index");
        }
        $charaInfo["upload_type"] = safe_GetPost("upload_type");
        $charaInfo["uuid"] = safe_GetPost("uuid");
        $charaInfo["passwd"] = safe_GetPost("passwd");
        $charaInfo["mac_id"] = safe_GetPost("mac_id");
        $charaInfo["name"] = safe_GetPost("name");
        $charaInfo["voicetype"] = safe_GetPost("voicetype");
        $charaInfo["birthmonth"] = safe_GetPost("birthmonth");
        $charaInfo["birthday"] = safe_GetPost("birthday");
        $charaInfo["bloodtype"] = safe_GetPost("bloodtype");
        $charaInfo["comment"] = safe_GetPost("comment");
        $charaInfo["package"] = safe_GetPost("package");
        $charaInfo["sex"] = safe_GetPost("sex");
        $charaInfo["height"] = safe_GetPost("height");
        $charaInfo["bust"] = safe_GetPost("bust");
        $charaInfo["hair"] = safe_GetPost("hair");

        $pngTmpName = safe_GetFileTmpName("png");
        
        if($mode == 2) {
            $retID = db_Chara::Upload($charaInfo, $pngTmpName);
            if($retID != NULL) {
                echo $retID;
            }
            else {
                echo "ERROR_UPLOAD_FAILD";
            }
        }
        else {
            if(db_Chara::Update($charaInfo, $pngTmpName)) {
                echo "S_OK";
            }
            else {
                echo "ERROR_UPDATE_FAILD";
            }
        }
    }
    else if($mode == 4) { //Download
        $index = safe_GetPost("index");
        $add_count = safe_GetPost("add_count");
        if($add_count > 0) {
            db_Chara::AddDLCount($index, $add_count);
        }
        
        $png = db_Chara::GetPNG($index);
        if(!$png) {
            echo "ERROR_GET_PNG_ERROR";
        }
        else {
            echo $png;
        }
    }
    else if($mode == 5) { //User Delete
        $index = safe_GetPost("index");
        $uuid = safe_GetPost("uuid");
        $passwd = safe_GetPost("passwd");

        if(db_Chara::UserDelete($index, $uuid, $passwd)) {
            echo "S_OK";
        }
        else {
            echo "ERROR_DELETE_FAILD";
        }
    }
?>