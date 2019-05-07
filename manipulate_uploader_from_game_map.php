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
    include_once("inc/pngData.php");
    include_once("inc/mysql/mysqlHandle.php");
    include_once("inc/mysql/db_Map.php");
    
    checkCloseErrorReport();
    mysqlHandle::MySQL_Connect();

    if($mode == 0) { //Get All Info
        $lstCharaInfo = db_Map::GetAll();
        $writeLst = array();
        foreach($lstCharaInfo as $mapInfo) {
            $tempString = base64_encode($mapInfo["idx"])."\t".
                        base64_encode($mapInfo["uid"])."\t".
                        base64_encode($mapInfo["userID"])."\t".
                        base64_encode($mapInfo["name"])."\t".
                        base64_encode($mapInfo["mapset"])."\t".
                        base64_encode($mapInfo["object_num"])."\t".
                        base64_encode($mapInfo["comment"])."\t".
                        base64_encode($mapInfo["package"])."\t".
                        base64_encode($mapInfo["dlCount"])."\t".
                        base64_encode($mapInfo["weekCount"])."\t".
                        base64_encode($mapInfo["updateTime"])."\t".
                        base64_encode($mapInfo["update_idx"])."\t".
                        base64_encode($mapInfo["ratingTotal"])."\t".
                        base64_encode($mapInfo["rateCount"])."\t".
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
            array_push($lstThumbnail, db_Map::GetThumbnail($index));
        }
        if(count($lstThumbnail) > 0) {
            echo implode("\t", $lstThumbnail);
        }
        else {
            echo "ERROR_NO_MAP";
        }
    }
    else if($mode == 2 || $mode == 3) { //#Upload 2 / Update 3
        $mapInfo = array();

        if($mode == 2) {
            $mapInfo["uid"] = safe_GetPost("map_uid");
        }
        else {
            $mapInfo["index"] = safe_GetPost("index");
        }
        $mapInfo["upload_type"] = safe_GetPost("upload_type");
        $mapInfo["uuid"] = safe_GetPost("uuid");
        $mapInfo["passwd"] = safe_GetPost("passwd");
        $mapInfo["mac_id"] = safe_GetPost("mac_id");
        $mapInfo["name"] = safe_GetPost("name");
        $mapInfo["mapset"] = safe_GetPost("mapset");
        $mapInfo["object_num"] = safe_GetPost("object_num");
        $mapInfo["comment"] = safe_GetPost("comment");
        $mapInfo["package"] = safe_GetPost("package");

        $pngFileName = safe_GetFileTmpName("png");

        if($mode == 2) {
            $retID = db_Map::Upload($mapInfo, $pngFileName);
            if($retID != NULL) {
                echo $retID;
            }
            else {
                echo "ERROR_UPLOAD_FAILD";
            }
        }
        else {
            if(db_Map::Update($mapInfo, $pngFileName)) {
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
            db_Map::AddDLCount($index, $add_count);
        }
        
        $png = db_Map::GetPNG($index);
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

        if(db_Map::UserDelete($index, $uuid, $passwd)) {
            echo "S_OK";
        }
        else {
            echo "ERROR_DELETE_FAILD";
        }
    }
    else if($mode == 6) { //Evaluate
        $user_idx = safe_GetPost("user_idx");
        $indexs = safe_GetPost("indexs");
        $ratings = safe_GetPost("ratings");

        $lstIndex = explode("\t", $indexs);
        $lstRating = explode("\t", $ratings);

        for ($i = 0; $i < count($lstIndex); $i++) {
            db_Map::Evaluate($lstIndex[$i], $lstRating[$i]);
        } 

        echo "S_OK";
    }
?>