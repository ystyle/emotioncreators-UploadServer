<?php
    class db_User {
        public static function IsExistUser($uuid, $passwd) {
            return mysqlHandle::MySQL_IsExistData2('userInfo', 'uuid', $uuid, 'passwd', $passwd);
        }
        public static function CreateUser($uuid, $passwd) {
            $uuid = mysql_real_escape_string($uuid);
            $passwd = mysql_real_escape_string($passwd);

            $sql = "insert into `userInfo`(
                `uuid`,`passwd`,`handleName`,`mapPoint`,`posePoint`,`scenePoint`,`mapCount`,`poseCount`,`sceneCount`
                ) values (
                '$uuid', '$passwd','NoName', 0, 0, 0, 0, 0, 0)";
   
            $result = mysql_query($sql);
            if(!$result) {
				return FALSE;
			}
            return TRUE;
        }

        public static function GetUserInfo($uuid, $passwd) {
            $uuid = mysql_real_escape_string($uuid);
            $passwd = mysql_real_escape_string($passwd);

            $sql = "select * from `userInfo` where `uuid`='{$uuid}' and `passwd`='{$passwd}'";
            $result = mysql_query($sql);
            if(!$result) {
				return NULL;
            }
            $userInfo = NULL;
			while($row = mysql_fetch_array($result)) {
                $userInfo = $row;
            }
            return $userInfo;
        }

        public static function GetUserID($uuid, $passwd) {
            $uuid = mysql_real_escape_string($uuid);
            $passwd = mysql_real_escape_string($passwd);

            return mysqlHandle::MySQL_GetData2('userInfo', 'uuid', $uuid, 'passwd', $passwd, 'userIdx');
        }

        public static function UpdateUserName($uuid, $passwd, $handleName) {
            $uuid = mysql_real_escape_string($uuid);
            $passwd = mysql_real_escape_string($passwd);
            $handleName = mysql_real_escape_string($handleName);
            
            return mysqlHandle::MySQL_UpdateData2('userInfo', 'handleName', $handleName, 'uuid', $uuid, 'passwd', $passwd);
        }

        public static function GetAllUsers() {
            $sql = "select * from `userInfo`";
            $result = mysql_query($sql);
            if(!$result) {
				return NULL;
            }
            $lstUserInfo = array();
			while($row = mysql_fetch_array($result)) {
                array_push($lstUserInfo,  $row);
            }
            return $lstUserInfo;
        }
    }
?>