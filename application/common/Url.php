<?php
/**
* Created by PhpStorm.
* User: leo
* Date: 2018/8/10
* Time: 10:11
*/
namespace app\common;

class Url{
    /**
     * 获取头像
     *
     * @Author : whoSafe
     *
     * @static :静态方法
     *
     * @Example:self::getAvatar(123,100)
     *
     * @param int $userId   用户id
     * @param int $size     图片大小
     *
     * @return string
     */
    public static function getAvatar($userId , $size = '120') {
        return  WWW.'header.png';
        $img_path = "avatar/0" . rtrim(chunk_split(sprintf("%08d" , $userId) , 2 , "/") , "/") . "_avatar_{$size}_{$size}.jpg";
        $path = "upload/" . $img_path;
        if (file_exists(ROOT . $path)) {
            //if(tool::getUrlExists(self::UrlRtrim(TUPIC).$path)===true){
            return WWW . $img_path;
        } else {
            $index = $userId%5+1;
            return self::UrlRtrim(WWW) . 'images/weibo/noavatar'.$index.'_' . $size . "_" . $size . ".jpg";
        }
    }
}