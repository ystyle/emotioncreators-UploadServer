<?php
    include_once('inc/utils.php');
    include_once('inc/pngData.php');
    include_once('inc/mysql/db_User.php');

    date_default_timezone_set("PRC");

    class db_Map {
        public static function GetAll() {
            $sql = "select * from `mapInfo`";
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
        
        public static function Upload($mapInfo, $pngTmpName) {
            $userID = db_User::GetUserID($mapInfo['uuid'], $mapInfo['passwd']);
            if($userID == NULL) {
                return NULL;
            }
            
            $mapInfo["uid"] = mysql_real_escape_string($mapInfo["uid"]);
            $mapInfo["uuid"] = mysql_real_escape_string($mapInfo["uuid"]);
            $mapInfo["passwd"] = mysql_real_escape_string($mapInfo["passwd"]);
            $mapInfo["mac_id"] = mysql_real_escape_string($mapInfo["mac_id"]);
            $mapInfo["name"] = mysql_real_escape_string($mapInfo["name"]);
            $mapInfo["comment"] = mysql_real_escape_string($mapInfo["comment"]);
            $mapInfo["package"] = mysql_real_escape_string($mapInfo["package"]);
            
            $sql = "insert into `mapInfo`(
            `upload_type`, `userID`, `uid`, `uuid`, `passwd`, `mac_id`, `name`,
            `mapset`, `object_num`, `comment`, `package`,
            `dlCount`, `weekCount`, `updateTime`, `update_idx`, `ratingTotal`, `rateCount`
            ) values (
            {$mapInfo["upload_type"]}, 
            {$userID}, 
            '{$mapInfo["uid"]}', 
            '{$mapInfo["uuid"]}', 
            '{$mapInfo["passwd"]}', 
            '{$mapInfo["mac_id"]}', 
            '{$mapInfo["name"]}', 
            {$mapInfo["mapset"]}, 
            {$mapInfo["object_num"]}, 
            '{$mapInfo["comment"]}', 
            '{$mapInfo["package"]}', 
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
            if(!pngData::SaveMap($insert_id, $pngTmpName)) {
                self::Delete($insert_id);
                return NULL;
            }
            return $insert_id;
        }

        public static function Update($mapInfo, $pngTmpName) {
            if(self::UserCheck($mapInfo['index'], $mapInfo['uuid'], $mapInfo['passwd']) == FALSE) {
                return FALSE;
            }

            if(!pngData::SaveMap($mapInfo['index'], $pngTmpName)) {
                return FALSE;
            }

            $mapInfo["uuid"] = mysql_real_escape_string($mapInfo["uuid"]);
            $mapInfo["passwd"] = mysql_real_escape_string($mapInfo["passwd"]);
            $mapInfo["mac_id"] = mysql_real_escape_string($mapInfo["mac_id"]);
            $mapInfo["name"] = mysql_real_escape_string($mapInfo["name"]);
            $mapInfo["comment"] = mysql_real_escape_string($mapInfo["comment"]);
            $mapInfo["package"] = mysql_real_escape_string($mapInfo["package"]);

            $sql = "update `mapInfo` set 
            `upload_type`= {$mapInfo['upload_type']},
            `uuid`='{$mapInfo['uuid']}',
            `passwd`='{$mapInfo['passwd']}',
            `mac_id`='{$mapInfo['mac_id']}',
            `name`='{$mapInfo['name']}',
            `mapset`={$mapInfo['mapset']},
            `object_num`={$mapInfo['object_num']},
            `comment`='{$mapInfo['comment']}',
            `package`='{$mapInfo['package']}',
            `updateTime`='".date("Y-m-d H:i:s",time())."',
            `update_idx`=0
             where 
             `idx`='{$mapInfo['index']}' and 
             `uuid`='{$mapInfo['uuid']}' and 
             `passwd`='{$mapInfo['passwd']}'";

             $result = mysql_query($sql);
             if(!$result) {
                 return FALSE;
             }
             return TRUE;
        }

        
        public static function UserCheck($index, $uuid, $passwd) {
            $charaUuid = mysqlHandle::MySQL_GetData('mapInfo', 'idx', $index, 'uuid');
            $charaPasswd = mysqlHandle::MySQL_GetData('mapInfo', 'idx', $index, 'passwd');
            if($charaUuid == $uuid && $charaPasswd == $passwd) {
                return TRUE;
            }
            return FALSE;
        }

        public static function AddDLCount($index, $add_count) {
            $oriCount = mysqlHandle::MySQL_GetData('mapInfo', 'idx', $index, 'dlCount');
            $oriWeekCount = mysqlHandle::MySQL_GetData('mapInfo', 'idx', $index, 'weekCount');
            mysqlHandle::MySQL_UpdateData('mapInfo', 'dlCount', $oriCount + $add_count, 'idx', $index);
            mysqlHandle::MySQL_UpdateData('mapInfo', 'weekCount', $oriWeekCount + $add_count, 'idx', $index);
        }

        public static function UserDelete($index, $uuid, $passwd) {
            if(self::UserCheck($index, $uuid, $passwd)) {
                return self::Delete($index);
            }
            return FALSE;
        }

        public static function Delete($index) {
            pngData::DeleteMap($index);
            return mysqlHandle::MySQL_DeleteData('mapInfo', 'idx', $index);
        }

        public static function GetThumbnail($index) {
            return pngData::GetMapThumbnail($index);
        }

        public static function GetPNG($index) {
            return pngData::GetMapPng($index);
        }

        public static function Evaluate($index, $Rating) {
            $oriRatingTotal = mysqlHandle::MySQL_GetData('mapInfo', 'idx', $index, 'ratingTotal');
            $oriRateCount = mysqlHandle::MySQL_GetData('mapInfo', 'idx', $index, 'rateCount');
            mysqlHandle::MySQL_UpdateData('mapInfo', 'ratingTotal', $oriRatingTotal + $Rating, 'idx', $index);
            mysqlHandle::MySQL_UpdateData('mapInfo', 'rateCount', $oriRateCount + $Rating, 'idx', $index);
        }
    }
?>