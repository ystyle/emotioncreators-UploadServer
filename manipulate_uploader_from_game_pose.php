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
    include_once("inc/mysql/db_Pose.php");
    
    checkCloseErrorReport();
    mysqlHandle::MySQL_Connect();

    if($mode == 0) { //Get All Info
        $lstCharaInfo = db_Pose::GetAll();
        $writeLst = array();
        foreach($lstCharaInfo as $poseInfo) {
            $tempString = base64_encode($poseInfo["idx"])."\t".
                        base64_encode($poseInfo["uid"])."\t".
                        base64_encode($poseInfo["userID"])."\t".
                        base64_encode($poseInfo["name"])."\t".
                        base64_encode($poseInfo["comment"])."\t".
                        base64_encode($poseInfo["dlCount"])."\t".
                        base64_encode($poseInfo["weekCount"])."\t".
                        base64_encode($poseInfo["updateTime"])."\t".
                        base64_encode($poseInfo["update_idx"])."\t".
                        base64_encode($poseInfo["ratingTotal"])."\t".
                        base64_encode($poseInfo["rateCount"])."\t".
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
            array_push($lstThumbnail,  db_Pose::GetThumbnail($index));
        }
        if(count($lstThumbnail) > 0) {
            echo implode("\t", $lstThumbnail);
        }
        else {
            echo "ERROR_NO_POSE";
        }
    }
    else if($mode == 2 || $mode == 3) { //#Upload 2 / Update 3
        $poseInfo = array();

        if($mode == 2) {
            $poseInfo["uid"] = safe_GetPost("pose_uid");
        }
        else {
            $poseInfo["index"] = safe_GetPost("index");
        }
        $poseInfo["upload_type"] = safe_GetPost("upload_type");
        $poseInfo["uuid"] = safe_GetPost("uuid");
        $poseInfo["passwd"] = safe_GetPost("passwd");
        $poseInfo["mac_id"] = safe_GetPost("mac_id");
        $poseInfo["name"] = safe_GetPost("name");
        $poseInfo["comment"] = safe_GetPost("comment");

        $pngFileName = safe_GetFileTmpName("png");

        if($mode == 2) {
            $retID = db_Pose::Upload($poseInfo, $pngFileName);
            if($retID != NULL) {
                echo $retID;
            }
            else {
                echo "ERROR_UPLOAD_FAILD";
            }
        }
        else {
            if(db_Pose::Update($poseInfo, $pngFileName)) {
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
            db_Pose::AddDLCount($index, $add_count);
        }
        
        $png = db_Pose::GetPNG($index);
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

        if(db_Pose::UserDelete($index, $uuid, $passwd)) {
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
            db_Pose::Evaluate($lstIndex[$i], $lstRating[$i]);
        } 

        echo "S_OK";
    }
?>