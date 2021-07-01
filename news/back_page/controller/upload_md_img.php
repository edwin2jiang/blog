<?php

/**
 * @author  Z09418208_蒋伟伟
 * @tips 用于md编辑器中的图片上传，与upload_img方法有些不一样。
 * - 数据样例：
 * {
 *   success : 0 | 1,           // 0 表示上传失败，1 表示上传成功
 *   message : "提示的信息，上传成功或上传失败及错误信息等。",
 *   url     : "图片地址"        // 上传成功时才返回
 *  }
 */

require_once '../../util/util.php';

header("Content-type: application/json;charset=UTF-8");


$photo = $_FILES['editormd-image-file'];
$error = $photo['error'];

$res_message = array(
    'success' => 0,
    'message' => '',
    'url' => ''
);


$url = '';
$success = 0;

switch ($error) {
    case 0:
        $photoName = $photo['name'];
        // echo "您的个人照片为：" . $photoName . '<br/>';
        $myPicTmpName = $photo['tmp_name'];
        // echo "临时文件名", $myPicTmpName, "<br/>";

        // 调用对应方法来保证文件名一定不会重复
        $des_name = get_filename($photoName);
        $destination = '../../uploads/' . $des_name;

        if(is_windows()){
            // 文件名强制转码 (因为本地 windows 采用的 gbk 编码)
            $destination = iconv("utf-8", 'gbk', $destination);
        }

        move_uploaded_file($myPicTmpName, $destination);

        $url = '../uploads/' . $des_name;

        // $res_message['message'] = "文件上传成功";
        unset($res_message['message']);

        $success = 1;
        break;
    case 1:
        $res_message['message'] = "文件上传超过了php.ini的限制";
        break;
    case 2:
        $res_message['message'] = "上传文件大小超过了Form表单中的MAX_FILE_SIZE选项的指定的值";
        break;
    case 3:
        $res_message['message'] = "文件只上传了部分";
        break;
    case 4:
        $res_message['message'] = "文件未选择";
        break;
    default:
        $res_message['message'] = "未知错误";
}

$res_message['success'] = $success;
$res_message['url'] = $url;

echo json_encode($res_message, JSON_UNESCAPED_UNICODE);
