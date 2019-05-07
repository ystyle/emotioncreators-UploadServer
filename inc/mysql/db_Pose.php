<?php
    include_once('inc/utils.php');
    include_once('inc/pngData.php');
    include_once('inc/mysql/db_User.php');

    date_default_timezone_set("PRC");

    class db_Pose {
        public static function GetAll() {
            $sql = "select * from `poseInfo`";
            $result = mysql_query($sql);
            if(!$result) {
				return NULL;
            }
            $lstInfo = array();
			while($row = mysql_fetch_array($result)) {
                array_push($lstInfo,  $row);
            }
            return $lstInfo;
        }
        
        public static function Upload($poseInfo, $pngTmpName) {
            $userID = db_User::GetUserID($poseInfo['uuid'], $poseInfo['passwd']);
            if($userID == NULL) {
                return NULL;
            }

            $poseInfo["uid"] = mysql_real_escape_string($poseInfo["uid"]);
            $poseInfo["uuid"] = mysql_real_escape_string($poseInfo["uuid"]);
            $poseInfo["passwd"] = mysql_real_escape_string($poseInfo["passwd"]);
            $poseInfo["mac_id"] = mysql_real_escape_string($poseInfo["mac_id"]);
            $poseInfo["name"] = mysql_real_escape_string($poseInfo["name"]);
            $poseInfo["comment"] = mysql_real_escape_string($poseInfo["comment"]);
            
            $sql = "insert into `poseInfo`(
            `upload_type`, `userID`, `uid`, `uuid`, `passwd`, `mac_id`, `name`,
            `comment`, 
            `dlCount`, `weekCount`, `updateTime`, `update_idx`, `ratingTotal`, `rateCount`
            ) values (
            {$poseInfo["upload_type"]}, 
            {$userID}, 
            '{$poseInfo["uid"]}', 
            '{$poseInfo["uuid"]}', 
            '{$poseInfo["passwd"]}', 
            '{$poseInfo["mac_id"]}', 
            '{$poseInfo["name"]}', 
            '{$poseInfo["comment"]}', 
            0, 
            0, 
            '".date("Y-m-d H:i:s",time())."', 
            0,
            0,
            0
            )";
  
            $result = mysql_query($sql);
            if(!$result) {
				return NULL;
            }
            $insert_id = mysql_insert_id();
            if(!pngData::SavePose($insert_id, $pngTmpName)) {
                self::Delete($insert_id);
                return NULL;
            }
            return $insert_id;
        }

        public static function Update($poseInfo, $pngTmpName) {
            if(self::UserCheck($poseInfo['index'], $poseInfo['uuid'], $poseInfo['passwd']) == FALSE) {
                return FALSE;
            }
            
            if(!pngData::SavePose($poseInfo['index'], $pngTmpName)) {
                return FALSE;
            }

            $poseInfo["uuid"] = mysql_real_escape_string($poseInfo["uuid"]);
            $poseInfo["passwd"] = mysql_real_escape_string($poseInfo["passwd"]);
            $poseInfo["mac_id"] = mysql_real_escape_string($poseInfo["mac_id"]);
            $poseInfo["name"] = mysql_real_escape_string($poseInfo["name"]);
            $poseInfo["comment"] = mysql_real_escape_string($poseInfo["comment"]);
            

            $sql = "update `poseInfo` set 
            `upload_type`= {$poseInfo['upload_type']},
            `uuid`='{$poseInfo['uuid']}',
            `passwd`='{$poseInfo['passwd']}',
            `mac_id`='{$poseInfo['mac_id']}',
            `name`='{$poseInfo['name']}',
            `comment`='{$poseInfo['comment']}',
            `updateTime`='".date("Y-m-d H:i:s",time())."',
            `update_idx`=0
             where 
             `idx`='{$poseInfo['index']}' and 
             `uuid`='{$poseInfo['uuid']}' and 
             `passwd`='{$poseInfo['passwd']}'";

             $result = mysql_query($sql);
             if(!$result) {
                 return FALSE;
             }
             return TRUE;
        }

        
        public static function UserCheck($index, $uuid, $passwd) {
            $charaUuid = mysqlHandle::MySQL_GetData('poseInfo', 'idx', $index, 'uuid');
            $charaPasswd = mysqlHandle::MySQL_GetData('poseInfo', 'idx', $index, 'passwd');
            if($charaUuid == $uuid && $charaPasswd == $passwd) {
                return TRUE;
            }
            return FALSE;
        }

        public static function AddDLCount($index, $add_count) {
            $oriCount = mysqlHandle::MySQL_GetData('poseInfo', 'idx', $index, 'dlCount');
            $oriWeekCount = mysqlHandle::MySQL_GetData('poseInfo', 'idx', $index, 'weekCount');
            mysqlHandle::MySQL_UpdateData('poseInfo', 'dlCount', $oriCount + $add_count, 'idx', $index);
            mysqlHandle::MySQL_UpdateData('poseInfo', 'weekCount', $oriWeekCount + $add_count, 'idx', $index);
        }

        public static function UserDelete($index, $uuid, $passwd) {
            if(self::UserCheck($index, $uuid, $passwd)) {
                return self::Delete($index);
            }
            return FALSE;
        }

        public static function Delete($index) {
            pngData::DeletePose($index);
            return mysqlHandle::MySQL_DeleteData('poseInfo', 'idx', $index);
        }

        public static function GetThumbnail($index) {
            return pngData::GetPoseThumbnail($index);
        }

        public static function GetPNG($index) {
            return pngData::GetPosePng($index);
        }

        public static function Evaluate($index, $Rating) {
            $oriRatingTotal = mysqlHandle::MySQL_GetData('poseInfo', 'idx', $index, 'ratingTotal');
            $oriRateCount = mysqlHandle::MySQL_GetData('poseInfo', 'idx', $index, 'rateCount');
            mysqlHandle::MySQL_UpdateData('poseInfo', 'ratingTotal', $oriRatingTotal + $Rating, 'idx', $index);
            mysqlHandle::MySQL_UpdateData('poseInfo', 'rateCount', $oriRateCount + $Rating, 'idx', $index);
        }
    }
?>