<?php
/**
 * 一个更好的数组结构输出方法
 * @param $array
 */

function pre_r($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

/**
 * 从右边检测 $str中第一次出现$chr的下标
 * @param $str
 * @param $chr
 * @return int
 */
function str_right_index($str, $chr)
{
    $end = strlen($str);
    for ($i = $end; $i >= 0; $i--) {
        if (substr($str, $i, 1) == $chr) {
            return $i;
        }
    }
    return -1;
}


/**
 * 从一串地址信息中解析出文件名字和拓展名 （例如 /uploads/example.tmp.png）
 * @param $path
 */
function get_filename_from_path($path)
{

    $index = str_right_index($path, '/');
    $name = substr($path, $index + 1);
    $dot_index = str_right_index($name, '.');
    $extend = substr($name, $dot_index);
    $prefix_name = substr($name, 0, strlen($name) - $dot_index);
    echo $name, '  ', $prefix_name;

}

// get_filename_from_path('/uploads/example.tmp.png');
// echo mb_substr("中文", 0, 2, 'utf-8');


/**
 * 对文件名进行重复性检查，来保证文件不会被覆盖掉。
 * @param $name 原来的文件名
 */
function get_filename($name)
{
    $index = str_right_index($name, '.');
    $prefix = substr($name, 0, $index);
    $extend = substr($name, $index);

    $tmp_name = $prefix . '_' . time() . '_' . mt_rand(1, 1000) . $extend;

    if (file_exists('../../uploads/' . $tmp_name)) {
        // 继续递归调用
        get_filename($name);
    } else {
        // 直到没有重复的
        return $tmp_name;
    }
}


/**
 * 判断字符串以$needle开始
 * @param $str
 * @param $needle
 * @return bool
 */
function start_with($str, $needle)
{
    return strpos($str, $needle) === 0;
}

/**
 * 判断当前操作系统是否是windows
 * @return bool
 */
function is_windows(){
    if (start_with(PHP_OS,"WIN")){
        return true;
    }else{
        return false;
    }
}
