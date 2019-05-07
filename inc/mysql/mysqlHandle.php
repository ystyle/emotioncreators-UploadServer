<?php
	class mysqlHandle {
		private static $isConnect = FALSE;

		public static function MySQL_Connect() {
			if(self::$isConnect == TRUE) {
				return;
			}

			$ini_array = parse_ini_file("data/config.ini");
	
			$con = mysql_connect($ini_array['host'], $ini_array['user'], $ini_array['password']);
			if(!$con){
				include('utils.php');
				writeError("MySQL 连接失败, 原因: ".mysql_error());
				die("SQLSTATE_MYSQL_ERROR");
			}
			
			mysql_select_db($ini_array['db'], $con);
			mysql_query("set names utf8");
			self::$isConnect = TRUE;
			self::CheckResetWeekDL();
		}

		public static function MySQL_Close()
		{
			if(self::$isConnect == TRUE) {
				mysql_close();
				self::$isConnect = FALSE;				
			}
		}

		public static function CheckResetWeekDL() {
			$fileName = "data/weekReset.txt";

			date_default_timezone_set("PRC");
			$weekDay = date("N",time());
			if($weekDay == 0 || $weekDay == 6) {
				if(!file_exists($fileName)) {
					mysql_query("update `charaInfo` set `weekCount`= 0");
					mysql_query("update `mapInfo` set `weekCount`= 0");
					mysql_query("update `poseInfo` set `weekCount`= 0");
					mysql_query("update `sceneInfo` set `weekCount`= 0");

					if($pFile = fopen($fileName, "w")) {
						fwrite($pFile, "重置用");
						fclose($pFile);
					}
				}
			}
			else {
				if(file_exists($fileName)) {
					unlink($fileName);
				}
			}
		}

		public static function MySQL_GetData($table, $whereA1, $whereA2, $rowIndex) {
			$whereA2 = mysql_real_escape_string($whereA2);

			$sql = "select * from `$table` where `$whereA1`='$whereA2'";
			$result = mysql_query($sql);
			if(!$result) {
				return NULL;
			}
			$retData = NULL;
			while($row = mysql_fetch_array($result)) {
				$retData = $row[$rowIndex];
			}
			return $retData;
		}

		public static function MySQL_GetData2($table, $whereA1, $whereA2, $whereB1, $whereB2, $rowIndex) {
			$whereA2 = mysql_real_escape_string($whereA2);
			$whereB2 = mysql_real_escape_string($whereB2);

			$sql = "select * from `$table` where `$whereA1`='$whereA2' and `$whereB1`='$whereB2'";
			$result = mysql_query($sql);
			if(!$result) {
				return NULL;
			}
			$retData = NULL;
			while($row = mysql_fetch_array($result)) {
				$retData = $row[$rowIndex];
			}
			return $retData;
		}

		public static function MySQL_DeleteData($table, $whereA1, $whereA2) {
			$whereA2 = mysql_real_escape_string($whereA2);

			$sql = "delete from `$table` where `$whereA1`='$whereA2'";
			$result = mysql_query($sql);
			if(!$result) {
				return FALSE;
			}
			return TRUE;
		}

		public static function MySQL_UpdateData($table, $updateA, $updateB, $whereA1, $whereA2) {
			$updateB = mysql_real_escape_string($updateB);
			$whereA2 = mysql_real_escape_string($whereA2);

			$sql = "update `$table` set `$updateA`='$updateB' where `$whereA1`='$whereA2'";
			$result = mysql_query($sql);
			if(!$result) {
				return FALSE;
			}
			return TRUE;
		}

		public static function MySQL_UpdateData2($table, $updateA, $updateB, $whereA1, $whereA2, $whereB1, $whereB2) {
			$updateB = mysql_real_escape_string($updateB);
			$whereA2 = mysql_real_escape_string($whereA2);
			$whereB2 = mysql_real_escape_string($whereB2);

			$sql = "update `$table` set `$updateA`='$updateB' where `$whereA1`='$whereA2' and `$whereB1`='$whereB2'";
			$result = mysql_query($sql);
			if(!$result) {
				return FALSE;
			}
			return TRUE;
		}

		public static function MySQL_IsExistData($table, $whereA1, $whereA2) {
			$whereA2 = mysql_real_escape_string($whereA2);

			$sql = "select * from `$table` where `$whereA1`='$whereA2'";
			$result = mysql_query($sql);
			if(!$result) {
				return FALSE;
			}
			$rows = mysql_num_rows($result);
			if(!$rows) {
				return FALSE;
			}
			return TRUE;
		}

		public static function MySQL_IsExistData2($table, $whereA1, $whereA2, $whereB1, $whereB2) {
			$whereA2 = mysql_real_escape_string($whereA2);
			$whereB2 = mysql_real_escape_string($whereB2);

			$sql = "select * from `$table` where `$whereA1`='$whereA2' and `$whereB1`='$whereB2'";
			$result = mysql_query($sql);
			if(!$result) {
				return FALSE;
			}
			$rows = mysql_num_rows($result);
			if(!$rows) {
				return FALSE;
			}
			return TRUE;
		}
	}
?>