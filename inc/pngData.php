<?php
    class pngData {
        private static $path_base_png = "data/pngData/png";
        private static $path_base_thumbnail = "data/pngData/thumbnail";

        // 角色
        public static function GetCharaPng($index) {
            return self::GetImage("chara", false, $index);
        }
        public static function GetCharaThumbnail($index) {
            return self::GetImage("chara", true, $index);
        }
        public static function SaveChara($index, $tmpName) {
            return self::SaveImage("chara", $index, $tmpName);
        }
        public static function DeleteChara($index) {
            self::DeleteImage("chara", $index);
        }

        // 地图
        public static function GetMapPng($index) {
            return self::GetImage("map", false, $index);
        }
        public static function GetMapThumbnail($index) {
            return self::GetImage("map", true, $index);
        }
        public static function SaveMap($index, $tmpName) {
            return self::SaveImage("map", $index, $tmpName);
        }
        public static function DeleteMap($index) {
            self::DeleteImage("map", $index);
        }

        // 姿势
        public static function GetPosePng($index) {
            return self::GetImage("pose", false, $index);
        }
        public static function GetPoseThumbnail($index) {
            return self::GetImage("pose", true, $index);
        }
        public static function SavePose($index, $tmpName) {
            return self::SaveImage("pose", $index, $tmpName);
        }
        public static function DeletePose($index) {
            self::DeleteImage("pose", $index);
        }

        // 场景
        public static function GetScenePng($index) {
            return self::GetImage("scene", false, $index);
        }
        public static function GetSceneThumbnail($index) {
            return self::GetImage("scene", true, $index);
        }
        public static function SaveScene($index, $tmpName) {
            return self::SaveImage("scene", $index, $tmpName);
        }
        public static function DeleteScene($index) {
            self::DeleteImage("scene", $index);
        }

        //其他
        private static function GetImage($type, $isThumb, $index) {
            $pathName = NULL;
            if(!$isThumb) {
                $pathName = self::$path_base_png."/".$type."/";
            } else {
                $pathName = self::$path_base_thumbnail."/".$type."/";
            }
            $ini_array = parse_ini_file("data/config.ini");
            if($ini_array["image_base64"]) {
                $fileName = $pathName."$index.base64";
                if(!file_exists($fileName)) {
                    return FALSE;
                }
                return file_get_contents($fileName);
            }
            $fileName = $pathName."$index.png";
            if(!file_exists($fileName)) {
                return FALSE;
            }
            $fileData = file_get_contents($fileName);
            if($fileData == FALSE) {
                return FALSE;
            }
            return base64_encode($fileData);
        }

        private static function SaveImage($type, $index, $tmpName) {
            if(!file_exists($tmpName)) {
                return FALSE;
            }

            if(!is_uploaded_file($tmpName)) {
                return FALSE;
            }
            $ini_array = parse_ini_file("data/config.ini");

            $fileName_NoExt_png = self::$path_base_png."/".$type."/".$index;
            $fileName_NoExt_thumbnail = self::$path_base_thumbnail."/".$type."/".$index;

            $fileName_png = "$fileName_NoExt_png.png";
            $fileName_thumbnail = "$fileName_NoExt_thumbnail.png";

            // 创建图片
            move_uploaded_file($tmpName, $fileName_png);

            // 创建缩略图
            $thumbnail_witdh = $ini_array["thumbnail_".$type."_witdh"];
            $thumbnail_height = $ini_array["thumbnail_".$type."_height"];
            if(!self::CreateThumbnail($fileName_png, $fileName_thumbnail, $thumbnail_witdh, $thumbnail_height)) {
                echo "CreateThumbnail false <br />";
                return FALSE;
            }

            // 如果启用了 base64 格式, 需要进行一遍转换
            if($ini_array["image_base64"]) {
                $fileName_base64_png = "$fileName_NoExt_png.base64";
                $fileName_base64_thumbnail = "$fileName_NoExt_thumbnail.base64";

                $pngBase64 = base64_encode(file_get_contents($fileName_png));
                unlink($fileName_png);
                file_put_contents($fileName_base64_png, $pngBase64);

                $thumbnailBase64 = base64_encode(file_get_contents($fileName_thumbnail));
                unlink($fileName_thumbnail);
                file_put_contents($fileName_base64_thumbnail, $thumbnailBase64);
            }
            return TRUE;
        }

        private static function CreateThumbnail($fileName, $saveName, $NewWidth, $NewHeight) {
            $thumbnail = imagecreatetruecolor($NewWidth, $NewHeight);
            $source = imagecreatefrompng($fileName);
            if(!$source) {
                return FALSE;
            }
            list($width, $height) = getimagesize($fileName);
            imagecopyresized($thumbnail, $source, 0, 0, 0, 0, $NewWidth, $NewHeight, $width, $height);
            imagepng($thumbnail, $saveName);
            return TRUE;
        }

        private static function DeleteImage($type, $index) {
            $pathName_png = self::$path_base_png."/".$type."/".$index;
            $pathName_thumbnail = self::$path_base_thumbnail."/".$type."/".$index;
            if(file_exists("$pathName_png.png")) {
                unlink("$pathName_png.png");
            }
            if(file_exists("$pathName_thumbnail.png")) {
                unlink("$pathName_thumbnail.png");
            }
            if(file_exists("$pathName_png.base64")) {
                unlink("$pathName_png.base64");
            }
            if(file_exists("$pathName_thumbnail.base64")) {
                unlink("$pathName_thumbnail.base64");
            }
        }
    }
?>