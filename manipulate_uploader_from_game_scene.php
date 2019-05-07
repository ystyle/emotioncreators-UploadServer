<?php
    $method = !empty($_GET) ? "GET" :"POST";
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
    include_once("inc/mysql/db_Scene.php");
    
    checkCloseErrorReport();
    mysqlHandle::MySQL_Connect();

    if($mode == 0) { //Get All Info
        $lstCharaInfo = db_Scene::GetAll();
        $writeLst = array();
        foreach($lstCharaInfo as $sceneInfo) {
            $tempString = base64_encode($sceneInfo["idx"])."\t".
                        base64_encode($sceneInfo["uid"])."\t".
                        base64_encode($sceneInfo["userID"])."\t".
                        base64_encode($sceneInfo["name"])."\t".
                        base64_encode($sceneInfo["mapset"])."\t".
                        base64_encode($sceneInfo["object_num"])."\t".
                        base64_encode($sceneInfo["male_num"])."\t".
                        base64_encode($sceneInfo["female_num"])."\t".
                        base64_encode($sceneInfo["is_adv"])."\t".
                        base64_encode($sceneInfo["is_h"])."\t".
                        base64_encode($sceneInfo["tag"])."\t".
                        base64_encode($sceneInfo["comment"])."\t".
                        base64_encode($sceneInfo["map_package"])."\t".
                        base64_encode($sceneInfo["chara_package"])."\t".
                        base64_encode($sceneInfo["dlCount"])."\t".
                        base64_encode($sceneInfo["weekCount"])."\t".
                        base64_encode($sceneInfo["updateTime"])."\t".
                        base64_encode($sceneInfo["update_idx"])."\t".
                        base64_encode($sceneInfo["ratingTotal"])."\t".
                        base64_encode($sceneInfo["rateCount"])."\t".
                        base64_encode($sceneInfo["playCount"])."\t".
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
            array_push($lstThumbnail,  db_Scene::GetThumbnail($index));
        }
        if(count($lstThumbnail) > 0) {
            $count = 0;
            foreach($lstThumbnail as $Thumbnail) {
                $count += strlen($Thumbnail);
            }
            echo implode("\t", $lstThumbnail);
        }
        else {
            echo "ERROR_NO_SCENE";
        }
    }
    else if($mode == 2 || $mode == 3) { //#Upload 2 / Update 3
        $sceneInfo = array();

        if($mode == 2) {
            $sceneInfo["uid"] = safe_GetPost("scene_uid");
        }
        else {
            $sceneInfo["index"] = safe_GetPost("index");
        }
        $sceneInfo["upload_type"] = safe_GetPost("upload_type");
        $sceneInfo["uuid"] = safe_GetPost("uuid");
        $sceneInfo["passwd"] = safe_GetPost("passwd");
        $sceneInfo["mac_id"] = safe_GetPost("mac_id");
        $sceneInfo["name"] = safe_GetPost("name");
        $sceneInfo["mapset"] = safe_GetPost("mapset");
        $sceneInfo["object_num"] = safe_GetPost("object_num");
        $sceneInfo["male_num"] = safe_GetPost("male_num");
        $sceneInfo["female_num"] = safe_GetPost("female_num");
        $sceneInfo["is_adv"] = safe_GetPost("is_adv");
        $sceneInfo["is_h"] = safe_GetPost("is_h");
        $sceneInfo["tag"] = safe_GetPost("tag");
        $sceneInfo["comment"] = safe_GetPost("comment");
        $sceneInfo["map_package"] = safe_GetPost("map_package");
        $sceneInfo["chara_package"] = safe_GetPost("chara_package");

        $pngFileName = safe_GetFileTmpName("png");

        if($mode == 2) {
            $retID = db_Scene::Upload($sceneInfo, $pngFileName);
            if($retID != NULL) {
                echo $retID;
            }
            else {
                echo "ERROR_UPLOAD_FAILD";
            }
        }
        else {
            if(db_Scene::Update($sceneInfo, $pngFileName)) {
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
            db_Scene::AddDLCount($index, $add_count);
        }
        
        $png = db_Scene::GetPNG($index);
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

        if(db_Scene::UserDelete($index, $uuid, $passwd)) {
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
            db_Scene::Evaluate($lstIndex[$i], $lstRating[$i]);
        } 

        echo "S_OK";
    }
    else if($mode == 7) { //Add Play Count
        $user_idx = safe_GetPost("user_idx");
        $indexs = safe_GetPost("indexs");
        $counts = safe_GetPost("counts");

        $lstIndex = explode("\t", $indexs);
        $lstCount = explode("\t", $counts);

        for ($i = 0; $i < count($lstIndex); $i++) {
            db_Scene::AddPlayCount($lstIndex[$i], $lstCount[$i]);
        } 

        echo "S_OK";
    }
?>