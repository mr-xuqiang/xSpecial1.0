<?php
class FileUtil {

    /**
     * 建立文件夹
     *
     * @param string $aimUrl
     * @return viod
     */
    function createDir($aimUrl) {
        $aimUrl = str_replace('', '/', $aimUrl);
        $aimDir = '';
        $arr = explode('/', $aimUrl);
        $result = true;
        foreach ($arr as $str) {
            $aimDir .= $str . '/';
            if (!file_exists($aimDir)) {
                $result = mkdir($aimDir);
            }
        }
        return $result;
    }

    /**
     * 建立文件
     *
     * @param string $aimUrl 
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    function createFile($aimUrl, $overWrite = false) {
        if (file_exists($aimUrl) && $overWrite == false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite == true) {
            FileUtil :: unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        FileUtil :: createDir($aimDir);
        touch($aimUrl);
        return true;
    }

    /**
     * 移动文件夹
     *
     * @param string $oldDir
     * @param string $aimDir
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    function moveDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            FileUtil :: createDir($aimDir);
        }
        @ $dirHandle = opendir($oldDir);
        if (!$dirHandle) {
            return false;
        }
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                FileUtil :: moveFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                FileUtil :: moveDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        closedir($dirHandle);
        return rmdir($oldDir);
    }

    /**
     * 移动文件
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    function moveFile($fileUrl, $aimUrl, $overWrite = false) {
        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite = false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite = true) {
            FileUtil :: unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        FileUtil :: createDir($aimDir);
        rename($fileUrl, $aimUrl);
        return true;
    }

    /**
     * 删除文件夹
     *
     * @param string $aimDir
     * @return boolean
     */
    function unlinkDir($aimDir) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        if (!is_dir($aimDir)) {
            return false;
        }
        $dirHandle = opendir($aimDir);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($aimDir . $file)) {
                FileUtil :: unlinkFile($aimDir . $file);
            } else {
                FileUtil :: unlinkDir($aimDir . $file);
            }
        }
        closedir($dirHandle);
        return rmdir($aimDir);
    }

    /**
     * 删除文件
     *
     * @param string $aimUrl
     * @return boolean
     */
    function unlinkFile($aimUrl) {
        if (file_exists($aimUrl)) {
            unlink($aimUrl);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 复制文件夹
     *
     * @param string $oldDir
     * @param string $aimDir
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    function copyDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            FileUtil :: createDir($aimDir);
        }
        $dirHandle = opendir($oldDir);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                FileUtil :: copyFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                FileUtil :: copyDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        return closedir($dirHandle);
    }

    /**
     * 复制文件
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    function copyFile($fileUrl, $aimUrl, $overWrite = false) {
        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite == false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite == true) {
            FileUtil :: unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        FileUtil :: createDir($aimDir);
        copy($fileUrl, $aimUrl);
        return true;
    }

}
$html=$_POST["html"];
$html = str_replace('\"','"',$html);
$html = str_replace("\'","'",$html);
$css_path=$_POST["css_path"];
$js_path=$_POST["js_path"];
$plugin_path=$_POST["plugin_path"];
file_put_contents('../../output/index.html',$html);
// foreach($css_path as $v){
//     $t=explode("/", $v);
//     if($t[1]){
//         echo '../../output/com/'.$t[1].'/'.$t[2];
//         FileUtil::createDir('../../output/com/'.$t[1].'/'.$t[2]);
//         FileUtil::copyDir($t[1].'/'.$t[2],'../../output/com/'.$t[1].'/'.$t[2]);
//     }
// }
// foreach($js_path as $v){
//     $j=explode("/", $v);
//     if($j[1]){
//         echo '../../output/com/'.$j[1].'/'.$j[2];
//         FileUtil::createDir('../../output/com/'.$j[1].'/'.$j[2]);
//         FileUtil::copyDir($j[1].'/'.$j[2],'../../output/com/'.$j[1].'/'.$j[2]);
//     }
// }
FileUtil::unlinkDir('../../output/com/');
FileUtil::unlinkDir('../../output/framecom/');
foreach($css_path as $v){
    $t=explode("/", $v);
    if($t[1]){
        echo '../../output/com/'.$t[1].'/'.$t[2];
        FileUtil::createDir('../../output/com/'.$t[1].'/'.$t[2]);
        FileUtil::copyDir('../../com/'.$t[1].'/'.$t[2],'../../output/com/'.$t[1].'/'.$t[2]);
    }
}
foreach($js_path as $v){
    $j=explode("/", $v);
    if($j[1]){
        echo '../../output/com/'.$j[1].'/'.$j[2];
        FileUtil::createDir('../../output/com/'.$j[1].'/'.$j[2]);
        FileUtil::copyDir('../../com/'.$j[1].'/'.$j[2],'../../output/com/'.$j[1].'/'.$j[2]);
    }
}

foreach($plugin_path as $v){
    if($v){
        echo '../../output/framecom/'.$v;
        FileUtil::createDir('../../output/framecom/'.$v);
        FileUtil::copyDir('../../framecom/'.$v,'../../output/framecom/'.$v);
    }
}
FileUtil::createDir('../../output/framecom/bootstrap/');
FileUtil::copyDir('../../framecom/bootstrap/','../../output/framecom/bootstrap/');
FileUtil::createDir('../../output/framecom/grid/');
FileUtil::copyDir('../../framesource/css/grid/','../../output/framecom/grid/');
FileUtil::createDir('../../output/framecom/base/');
FileUtil::copyDir('../../framesource/css/base/','../../output/framecom/base/');
?>