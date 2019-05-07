<?php
    include_once('inc/utils.php');
    include_once('inc/pngData.php');
    include_once('inc/mysql/db_User.php');

    date_default_timezone_set("PRC");

    class db_Scene {
        public static function GetAll() {
            $sql = "select * from `sceneInfo`";
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
        
        public static function Upload($sceneInfo, $pngTmpName) {
            $userID = db_User::GetUserID($sceneInfo['uuid'], $sceneInfo['passwd']);
            if($userID == NULL) {
                return NULL;
            }

            $sceneInfo["uid"] = mysql_real_escape_string($sceneInfo["uid"]);
            $sceneInfo["uuid"] = mysql_real_escape_string($sceneInfo["uuid"]);
            $sceneInfo["passwd"] = mysql_real_escape_string($sceneInfo["passwd"]);
            $sceneInfo["mac_id"] = mysql_real_escape_string($sceneInfo["mac_id"]);
            $sceneInfo["name"] = mysql_real_escape_string($sceneInfo["name"]);
            $sceneInfo["tag"] = mysql_real_escape_string($sceneInfo["tag"]);
            $sceneInfo["comment"] = mysql_real_escape_string($sceneInfo["comment"]);
            $sceneInfo["map_package"] = mysql_real_escape_string($sceneInfo["map_package"]);
            $sceneInfo["chara_package"] = mysql_real_escape_string($sceneInfo["chara_package"]);
            
            $sql = "insert into `sceneInfo`(
            `upload_type`, `userID`, `uid`, `uuid`, `passwd`, `mac_id`, `name`,
            `mapset`,`object_num`,`male_num`,`female_num`,`is_adv`,`is_h`,`tag`,`comment`,`map_package`,`chara_package`, 
            `dlCount`, `weekCount`, `updateTime`, `update_idx`, `ratingTotal`, `rateCount`,`playCount`
            ) values (
            {$sceneInfo["upload_type"]},  
            {$userID}, 
            '{$sceneInfo["uid"]}', 
            '{$sceneInfo["uuid"]}', 
            '{$sceneInfo["passwd"]}', 
            '{$sceneInfo["mac_id"]}', 
            '{$sceneInfo["name"]}', 
            {$sceneInfo["mapset"]}, 
            {$sceneInfo["object_num"]}, 
            {$sceneInfo["male_num"]}, 
            {$sceneInfo["female_num"]}, 
            {$sceneInfo["is_adv"]}, 
            {$sceneInfo["is_h"]}, 
            '{$sceneInfo["tag"]}', 
            '{$sceneInfo["comment"]}', 
            '{$sceneInfo["map_package"]}', 
            '{$sceneInfo["chara_package"]}', 
            0, 
            0, 
            '".date("Y-m-d H:i:s",time())."', 
            0,
            0,
            0,
            0
            )";
  
            $result = mysql_query($sql);
            if(!$result) {
				return NULL;
            }
            $insert_id = mysql_insert_id();
            if(!pngData::SaveScene($insert_id, $pngTmpName)) {
                self::Delete($insert_id);
                return NULL;
            }
            return $insert_id;
        }

        public static function Update($sceneInfo, $pngTmpName) {
            if(self::UserCheck($sceneInfo['index'], $sceneInfo['uuid'], $sceneInfo['passwd']) == FALSE) {
                return FALSE;
            }

            if(!pngData::SaveScene($sceneInfo['index'], $pngTmpName)) {
                return FALSE;
            }

            $sceneInfo["uuid"] = mysql_real_escape_string($sceneInfo["uuid"]);
            $sceneInfo["passwd"] = mysql_real_escape_string($sceneInfo["passwd"]);
            $sceneInfo["mac_id"] = mysql_real_escape_string($sceneInfo["mac_id"]);
            $sceneInfo["name"] = mysql_real_escape_string($sceneInfo["name"]);
            $sceneInfo["tag"] = mysql_real_escape_string($sceneInfo["tag"]);
            $sceneInfo["comment"] = mysql_real_escape_string($sceneInfo["comment"]);
            $sceneInfo["map_package"] = mysql_real_escape_string($sceneInfo["map_package"]);
            $sceneInfo["chara_package"] = mysql_real_escape_string($sceneInfo["chara_package"]);

            $sql = "update `sceneInfo` set 
            `upload_type`= {$sceneInfo['upload_type']},
            `uuid`='{$sceneInfo['uuid']}',
            `passwd`='{$sceneInfo['passwd']}',
            `mac_id`='{$sceneInfo['mac_id']}',
            `name`='{$sceneInfo['name']}',
            `mapset`={$sceneInfo['mapset']},
            `object_num`={$sceneInfo['object_num']},
            `male_num`={$sceneInfo['male_num']},
            `female_num`={$sceneInfo['female_num']},
            `is_adv`={$sceneInfo['is_adv']},
            `is_h`={$sceneInfo['is_h']},
            `tag`='{$sceneInfo['tag']}',
            `comment`='{$sceneInfo['comment']}',
            `map_package`='{$sceneInfo['map_package']}',
            `chara_package`='{$sceneInfo['chara_package']}',
            `updateTime`='".date("Y-m-d H:i:s",time())."',
            `update_idx`=0
             where 
             `idx`='{$sceneInfo['index']}' and 
             `uuid`='{$sceneInfo['uuid']}' and 
             `passwd`='{$sceneInfo['passwd']}'";

             $result = mysql_query($sql);
             if(!$result) {
                 return FALSE;
             }
             return TRUE;
        }

        
        public static function UserCheck($index, $uuid, $passwd) {
            $charaUuid = mysqlHandle::MySQL_GetData('sceneInfo', 'idx', $index, 'uuid');
            $charaPasswd = mysqlHandle::MySQL_GetData('sceneInfo', 'idx', $index, 'passwd');
            if($charaUuid == $uuid && $charaPasswd == $passwd) {
                return TRUE;
            }
            return FALSE;
        }
        
        public static function AddDLCount($index, $add_count) {
            $oriCount = mysqlHandle::MySQL_GetData('sceneInfo', 'idx', $index, 'dlCount');
            $oriWeekCount = mysqlHandle::MySQL_GetData('sceneInfo', 'idx', $index, 'weekCount');
            mysqlHandle::MySQL_UpdateData('sceneInfo', 'dlCount', $oriCount + $add_count, 'idx', $index);
            mysqlHandle::MySQL_UpdateData('sceneInfo', 'weekCount', $oriWeekCount + $add_count, 'idx', $index);
        }
        public static function UserDelete($index, $uuid, $passwd) {
            if(self::UserCheck($index, $uuid, $passwd)) {
                return self::Delete($index);
            }
            return FALSE;
        }

        public static function Delete($index) {
            pngData::DeleteScene($index);
            return mysqlHandle::MySQL_DeleteData('sceneInfo', 'idx', $index);
        }

        public static function GetThumbnail($index) {
            return pngData::GetSceneThumbnail($index);
        }

        public static function GetPNG($index) {
            return pngData::GetScenePng($index);
        }

        public static function Evaluate($index, $rating) {
            $oriRatingTotal = mysqlHandle::MySQL_GetData('sceneInfo', 'idx', $index, 'ratingTotal');
            $oriRateCount = mysqlHandle::MySQL_GetData('sceneInfo', 'idx', $index, 'rateCount');
            mysqlHandle::MySQL_UpdateData('sceneInfo', 'ratingTotal', $oriRatingTotal + $rating, 'idx', $index);
            mysqlHandle::MySQL_UpdateData('sceneInfo', 'rateCount', $oriRateCount + $rating, 'idx', $index);
        }

        public static function AddPlayCount($index, $count) {
            $oriPlayCount = mysqlHandle::MySQL_GetData('sceneInfo', 'idx', $index, 'playCount');
            mysqlHandle::MySQL_UpdateData('sceneInfo', 'playCount', $oriPlayCount + $count, 'idx', $index);
        }
    }
?>