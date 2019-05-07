<?php
    include_once('inc/pngData.php');
    include_once('inc/mysql/db_User.php');

    date_default_timezone_set("PRC");

    class db_Chara {
        public static function GetAll() {
            $sql = "select * from `charaInfo`";
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

        public static function Upload($charaInfo, $pngTmpName) {
            $userID = db_User::GetUserID($charaInfo['uuid'], $charaInfo['passwd']);
            if($userID == NULL) {
                return NULL;
            }
            
            $charaInfo["uid"] = mysql_real_escape_string($charaInfo["uid"]);
            $charaInfo["uuid"] = mysql_real_escape_string($charaInfo["uuid"]);
            $charaInfo["passwd"] = mysql_real_escape_string($charaInfo["passwd"]);
            $charaInfo["mac_id"] = mysql_real_escape_string($charaInfo["mac_id"]);
            $charaInfo["name"] = mysql_real_escape_string($charaInfo["name"]);
            $charaInfo["comment"] = mysql_real_escape_string($charaInfo["comment"]);
            $charaInfo["package"] = mysql_real_escape_string($charaInfo["package"]);

            $sql = "insert into `charaInfo`(
            `upload_type`, `userID`, `uid`, `uuid`, `passwd`, `mac_id`, `name`,
            `voicetype`, `birthmonth`, `birthday`, `bloodtype`, `comment`, `package`, `sex`, `height`, `bust`, `hair`,
            `dlCount`, `weekCount`, `updateTime`, `update_idx`
            ) values (
            {$charaInfo["upload_type"]}, 
            {$userID}, 
            '{$charaInfo["uid"]}', 
            '{$charaInfo["uuid"]}', 
            '{$charaInfo["passwd"]}', 
            '{$charaInfo["mac_id"]}', 
            '{$charaInfo["name"]}', 
            {$charaInfo["voicetype"]}, 
            {$charaInfo["birthmonth"]}, 
            {$charaInfo["birthday"]}, 
            {$charaInfo["bloodtype"]}, 
            '{$charaInfo["comment"]}', 
            '{$charaInfo["package"]}', 
            {$charaInfo["sex"]}, 
            {$charaInfo["height"]}, 
            {$charaInfo["bust"]}, 
            {$charaInfo["hair"]}, 
            0, 
            0, 
            '".date("Y-m-d H:i:s",time())."', 
            0
            )";
  
            $result = mysql_query($sql);
            if(!$result) {
				return NULL;
            }
            $insert_id = mysql_insert_id();
            if(!pngData::SaveChara($insert_id, $pngTmpName)) {
                self::Delete($insert_id);
                return NULL;
            }
            return $insert_id;
        }

        public static function Update($charaInfo, $pngTmpName) {
            if(self::UserCheck($charaInfo['index'], $charaInfo['uuid'], $charaInfo['passwd']) == FALSE) {
                return FALSE;
            }

            if(!pngData::SaveChara($charaInfo['index'], $pngTmpName)) {
                return FALSE;
            }

            $charaInfo["uuid"] = mysql_real_escape_string($charaInfo["uuid"]);
            $charaInfo["passwd"] = mysql_real_escape_string($charaInfo["passwd"]);
            $charaInfo["mac_id"] = mysql_real_escape_string($charaInfo["mac_id"]);
            $charaInfo["name"] = mysql_real_escape_string($charaInfo["name"]);
            $charaInfo["comment"] = mysql_real_escape_string($charaInfo["comment"]);
            $charaInfo["package"] = mysql_real_escape_string($charaInfo["package"]);

            $sql = "update `charaInfo` set 
            `upload_type`= {$charaInfo['upload_type']},
            `uuid`='{$charaInfo['uuid']}',
            `passwd`='{$charaInfo['passwd']}',
            `mac_id`='{$charaInfo['mac_id']}',
            `name`='{$charaInfo['name']}',
            `voicetype`={$charaInfo['voicetype']},
            `birthmonth`={$charaInfo['birthmonth']},
            `birthday`={$charaInfo['birthday']},
            `bloodtype`={$charaInfo['bloodtype']},
            `comment`='{$charaInfo['comment']}',
            `package`='{$charaInfo['package']}',
            `sex`={$charaInfo['sex']},
            `height`={$charaInfo['height']},
            `bust`={$charaInfo['bust']},
            `hair`={$charaInfo['hair']},
            `updateTime`='".date("Y-m-d H:i:s",time())."',
            `update_idx`=0
             where 
             `idx`='{$charaInfo['index']}' and 
             `uuid`='{$charaInfo['uuid']}' and 
             `passwd`='{$charaInfo['passwd']}'";

             $result = mysql_query($sql);
             if(!$result) {
                 return FALSE;
             }
             return TRUE;
        }

        
        public static function UserCheck($index, $uuid, $passwd) {
            $charaUuid = mysqlHandle::MySQL_GetData('charaInfo', 'idx', $index, 'uuid');
            $charaPasswd = mysqlHandle::MySQL_GetData('charaInfo', 'idx', $index, 'passwd');
            if($charaUuid == $uuid && $charaPasswd == $passwd) {
                return TRUE;
            }
            return FALSE;
        }
        
        public static function AddDLCount($index, $add_count) {
            $oriCount = mysqlHandle::MySQL_GetData('charaInfo', 'idx', $index, 'dlCount');
            $oriWeekCount = mysqlHandle::MySQL_GetData('charaInfo', 'idx', $index, 'weekCount');
            mysqlHandle::MySQL_UpdateData('charaInfo', 'dlCount', $oriCount + $add_count, 'idx', $index);
            mysqlHandle::MySQL_UpdateData('charaInfo', 'weekCount', $oriWeekCount + $add_count, 'idx', $index);
        }

        public static function UserDelete($index, $uuid, $passwd) {
            if(self::UserCheck($index, $uuid, $passwd)) {
                return self::Delete($index);
            }
            return FALSE;
        }

        public static function Delete($index) {
            pngData::DeleteChara($index);
            return mysqlHandle::MySQL_DeleteData('charaInfo', 'idx', $index);
        }
        
        public static function GetThumbnail($index) {
            return pngData::GetCharaThumbnail($index);
        }

        public static function GetPNG($index) {
            return pngData::GetCharaPng($index);
        }
    }
?>