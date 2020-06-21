<?php
//复制图片
function file2dir($sourcefile, $dir, $filename)
{
    if (!file_exists($sourcefile)) {
        return false;
    }
    //$filename = basename($sourcefile);
    return copy($sourcefile, $dir . '' . $filename);
}
$src=$_POST['src'];
file2dir($src, "../travel-image/large/", "src");
file2dir($src, "../travel-image/square-medium/", "src");
echo '';
?>